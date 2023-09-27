<?php

namespace Leafwrap\SocialConnects\Contracts;

interface ProviderContract
{
    public function tokenizer($data);
    public function urlGen();
    public function authRequest();
    public function authResponse($data);
}
