<?php

use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    public function testCanBeCreatedFromValidRoute(): void
    {
        $this->assertInstanceOf(
            Route::class,
            Route::fromString('/create')
        );
    }

    public function testCannotBeCreatedFromInvalidRoute(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Route::fromString('/some-invalid-route');
    }

    public function testCannotBeCreatedFromEmptyRoute(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Route::fromString(null);
    }

    public function testCanBeUsedAsString(): void
    {
        $this->assertEquals(
            '/answer',
            Route::fromString('/answer')
        );
    }
}