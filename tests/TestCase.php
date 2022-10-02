<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\MakeJsonApiRequests;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, MakeJsonApiRequests;
}
