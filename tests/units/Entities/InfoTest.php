<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Repositories\InfoRepository;
use App\Entities\Info;

class InfoTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function info_has_code_description_fields()
    {
        $code = 'test';
        $description = 'Test Description';
        $info = factory(Info::class)->create(compact('code', 'description'));

        $this->assertEquals($code, $info->code);
        $this->assertEquals($description, $info->description);
    }

    /** @test */
    function info_has_required_code_and_description_fields()
    {
        $code = 'test';
        $description = 'Test Description';
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);
        $this->app->make(InfoRepository::class)->create(compact('description'));
        $this->setExpectedException(Prettus\Validator\Exceptions\ValidatorException::class);
        $this->app->make(InfoRepository::class)->create(compact('code'));
    }

    /** @test */
    function info_has_unique_code_field()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        $code = 'test';
        $description = 'Test Description 1';
        factory(Info::class)->create(compact('code', 'description'));

        $description = 'Test Description 2';
        factory(Info::class)->create(compact('code', 'description'));
    }
}
