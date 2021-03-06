<?php
require_once __DIR__ . '/../bootstrap.php';

$loop = EventLoop\getLoop();

$httpd = new \Rxnet\Httpd\Httpd();

$httpd->route('GET', '/foo', function(\Rxnet\Httpd\HttpdRequest $request, \Rxnet\Httpd\HttpdResponse $response) use ($loop) {
    $loop->addTimer(4, function () use ($response) {
        printf("Return /foo results\n");
        $response->json([
            [
                'id' => 1,
                'value' => 'foo1',
            ],
            [
                'id' => 2,
                'value' => 'foo2',
            ],
            [
                'id' => 3,
                'value' => 'foo3',
            ],
        ]);
    });
});

$httpd->route('GET', '/bar', function(\Rxnet\Httpd\HttpdRequest $request, \Rxnet\Httpd\HttpdResponse $response) {
    printf("Return /bar results\n");
    $response->json([
        [
            'id' => 1,
            'value' => 'bar1',
        ],
        [
            'id' => 2,
            'value' => 'bar2',
        ],
        [
            'id' => 3,
            'value' => 'bar3',
        ],
    ]);
});

$httpd->route('GET', '/foobar', function(\Rxnet\Httpd\HttpdRequest $request, \Rxnet\Httpd\HttpdResponse $response) {
    printf("Return /foobar results\n");
    $response->json([
        [
            'id' => 1,
            'value' => 'foobar1',
        ],
        [
            'id' => 2,
            'value' => 'foobar2',
        ],
        [
            'id' => 3,
            'value' => 'foobar3',
        ],
    ]);
});

$i = 0;
$httpd->route('GET', '/barfoo', function(\Rxnet\Httpd\HttpdRequest $request, \Rxnet\Httpd\HttpdResponse $response) use (&$i) {
    if ($i < 2) {
        printf("/barfoo returns error\n");
        $response->sendError('Something went wrong');
        $i++;
        return;
    }
    $i = 0;
    printf("Return /barfoo results\n");
    $response->json([
        [
            'id' => 1,
            'value' => 'barfoo1',
        ],
        [
            'id' => 2,
            'value' => 'barfoo2',
        ],
        [
            'id' => 3,
            'value' => 'barfoo3',
        ],
    ]);
});

$httpd->listen(23080);
printf("[%s]Server Listening on 23080\n", date('H:i:s'));
$loop->run();