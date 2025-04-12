<?php

namespace api\tests\unit\controllers;

use api\controllers\CourierController;
use PHPUnit\Framework\TestCase;

/**
 * Базовый тест для CourierController
 */
class BasicCourierTest extends TestCase
{
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new CourierController('courier', \Yii::$app);
    }

    public function testControllerExists()
    {
        $this->assertInstanceOf(CourierController::class, $this->controller);
    }

    public function testBehaviors()
    {
        $behaviors = $this->controller->behaviors();
        $this->assertIsArray($behaviors);
        $this->assertArrayHasKey('contentNegotiator', $behaviors);
        $this->assertArrayHasKey('corsFilter', $behaviors);
    }

    public function testModelClass()
    {
        $reflectionClass = new \ReflectionClass($this->controller);
        $property = $reflectionClass->getProperty('modelClass');
        $property->setAccessible(true);
        $this->assertEquals('common\models\Courier', $property->getValue($this->controller));
    }
} 