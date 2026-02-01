<?php

test('public page is available', function (string $url) {
    $client = $this->createClient();
    $client->request('GET', $url);
    $this->assertResponseIsSuccessful();
})->with(function () {
    yield 'homepage' => '/';
});

test('protected page is available', function (string $url) {
    $user = container()->get('user repository class goes here')->find(1);
    $client = $this->createClient()->loginUser($user);
    $client->request('GET', $url);
    $this->assertResponseIsSuccessful();
})->with(function () {
    yield 'Account' => '/my-account';
})->skip('To be implemented');
