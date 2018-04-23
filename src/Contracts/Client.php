<?php

namespace DeeToo\KickstarterSdk\Contracts;

interface Client
{
    public function request($verb, $url, array $request = []);
}
