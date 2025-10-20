<?php

namespace App\Helpers;

use Google\Cloud\Monitoring\V3\Client\MetricServiceClient;
use Google\Cloud\Monitoring\V3\Aggregation;
use Google\Cloud\Monitoring\V3\ListTimeSeriesRequest\TimeSeriesView;
use Google\Cloud\Monitoring\V3\ListTimeSeriesRequest;
use Google\Cloud\Monitoring\V3\TimeInterval;
use DateTime;
use DateTimeZone;
use DateInterval;
use Illuminate\Support\Facades\Log;
use Google\Protobuf\Timestamp;
use Google\Protobuf\Duration;

class CloudRunMetrics
{
    /**
     * Ambil metrik Cloud Run (request_count, latencies, instance_count)
     * lalu agregasikan per jam dalam GMT+7 selama 24 jam terakhir
     */
    public static function getMetrics(string $projectId, string $serviceName = 'courtplay-web'): array
    {
        $client = null;

        try {
            // === 1️⃣ Inisialisasi credentials ===
            $credPath = base_path(env('GOOGLE_APPLICATION_CREDENTIALS', 'storage/app/keys/courtplay-gcs-key.json'));
            if (!file_exists($credPath)) {
                throw new \Exception("Google credentials file not found at: $credPath");
            }
            putenv("GOOGLE_APPLICATION_CREDENTIALS=$credPath");

            $client = new MetricServiceClient();
            $projectName = $client->projectName($projectId);

            // === 2️⃣ Daftar metric type yang akan diambil ===
            $metricTypes = [
                'request_count'      => 'run.googleapis.com/request_count',
                'request_latencies'  => 'run.googleapis.com/request_latencies',
                'container_instances'=> 'run.googleapis.com/container/instance_count',
            ];

            // === 3️⃣ Rentang waktu 24 jam terakhir (UTC)
            $nowUtc = new DateTime('now', new DateTimeZone('UTC'));
            $startUtc = (clone $nowUtc)->sub(new DateInterval('P1D'));

            $startTimestamp = new Timestamp();
            $startTimestamp->setSeconds($startUtc->getTimestamp());
            $endTimestamp = new Timestamp();
            $endTimestamp->setSeconds($nowUtc->getTimestamp());

            $interval = (new TimeInterval())
                ->setStartTime($startTimestamp)
                ->setEndTime($endTimestamp);

            $metrics = [];

            // === 4️⃣ Ambil dan olah masing-masing metric ===
            foreach ($metricTypes as $key => $type) {
                // Filter metric
                $filter = sprintf('metric.type="%s" AND resource.labels.service_name="%s"', $type, $serviceName);

                // Pilih aligner sesuai metric
                if (str_contains($type, 'latencies')) {
                    $aligner = Aggregation\Aligner::ALIGN_PERCENTILE_50;
                } elseif (str_contains($type, 'request_count')) {
                    $aligner = Aggregation\Aligner::ALIGN_RATE;
                } else {
                    $aligner = Aggregation\Aligner::ALIGN_MEAN;
                }

                // Alignment period 5 menit
                $duration = new Duration();
                $duration->setSeconds(300);

                $aggregation = (new Aggregation())
                    ->setAlignmentPeriod($duration)
                    ->setPerSeriesAligner($aligner);

                // Request metric
                $request = (new ListTimeSeriesRequest())
                    ->setName($projectName)
                    ->setFilter($filter)
                    ->setInterval($interval)
                    ->setAggregation($aggregation)
                    ->setView(TimeSeriesView::FULL);

                $results = $client->listTimeSeries($request);

                // === 5️⃣ Ubah ke format [time,value] dan ubah zona waktu ke GMT+7 ===
                $rawPoints = [];
                foreach ($results as $ts) {
                    foreach ($ts->getPoints() as $p) {
                        $tUtc = $p->getInterval()->getEndTime()->toDateTime();
                        $tJakarta = (clone $tUtc)->setTimezone(new DateTimeZone('Asia/Jakarta'));

                        // Label jam misal "14:00"
                        $hourKey = $tJakarta->format('H:00');
                        $value = $p->getValue()->getDoubleValue() ?: $p->getValue()->getInt64Value();

                        if (str_contains($type, 'request_count')) {
                            // Rate per second → per hour
                            $value *= 3600;
                        } elseif (str_contains($type, 'latencies')) {
                            // Nanosecond → millisecond
                            $value /= 1e6;
                        }

                        // Tambahkan nilai
                        $rawPoints[$hourKey] = ($rawPoints[$hourKey] ?? 0) + $value;
                    }
                }

                // === 6️⃣ Normalisasi: pastikan semua 24 jam (isi yang kosong dengan 0)
                $filledPoints = [];
                $nowJakarta = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
                for ($i = 23; $i >= 0; $i--) {
                    $hourLabel = (clone $nowJakarta)->sub(new DateInterval("PT{$i}H"))->format('H:00');
                    $filledPoints[$hourLabel] = round($rawPoints[$hourLabel] ?? 0, 3);
                }

                $metrics[$key] = [[
                    'label' => $serviceName,
                    'points' => collect($filledPoints)->map(fn($v,$k)=>['time'=>$k,'value'=>$v])->values()->toArray()
                ]];
            }

            $client->close();

            // === 7️⃣ Return hasil
            return [
                'service' => $serviceName,
                'time_range' => [
                    'start' => $startUtc->format(DateTime::ATOM),
                    'end'   => $nowUtc->format(DateTime::ATOM),
                    'interval_minutes' => 60,
                ],
                'metrics' => $metrics,
            ];
        } catch (\Throwable $e) {
            Log::error('CloudRunMetrics fetch failed: ' . $e->getMessage());
            return [
                'error' => $e->getMessage(),
                'metrics' => [],
            ];
        } finally {
            if ($client) $client->close();
        }
    }
}
