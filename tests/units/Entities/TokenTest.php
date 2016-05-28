<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Repositories\ContactRepository;
use App\Repositories\GroupRepository;
use App\Repositories\TokenRepository;
use App\Entities\Contact;
use App\Entities\Token;
use App\Entities\Group;

class TokenTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function token_has_unique_code()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        $attributes = [
            'code'       => 'ABC1234',
            'class'      => Group::class,
            'reference'  => 1
        ];

        $this->app->make(TokenRepository::class)->create($attributes);
        $this->app->make(TokenRepository::class)->create($attributes);
    }

    /** @test */
    function token_has_unique_object_and_object_id()
    {
        $this->setExpectedException(Illuminate\Database\QueryException::class);

        $this->app->make(TokenRepository::class)->skipPresenter()->create([
            'code'       => 'ABC1234',
            'class'      => Group::class,
            'reference'  => 1
        ]);

        $this->app->make(TokenRepository::class)->skipPresenter()->create([
            'code'       => 'XYZ4321',
            'class'      => Group::class,
            'reference'  => 1
        ]);
    }

    /** @test */
    function tokens_can_be_claimed_by_a_contact_returning_the_object()
    {
        $group1 = App::make(GroupRepository::class)->skipPresenter()->create([
            'name' => "Test Group 1",
            'alias' => 'test1' //deprecate
        ]);

        $group2 = App::make(GroupRepository::class)->skipPresenter()->create([
            'name' => "Test Group 2",
            'alias' => 'test2' //deprecate
        ]);

        $tokens = App::make(TokenRepository::class)->skipPresenter();
        $claim_code = 'ABC1234';
        $multiple_usage_code = 'XYZ4321';
        $tokens->create([
            'code'       => $claim_code,
            'class'      => Group::class,
            'reference'  => $group1->id,
            'quota'      => 1,
        ]);
        $tokens->create([
            'code'       => $multiple_usage_code,
            'class'      => Group::class,
            'reference'  => $group2->id,
        ]);

        $this->assertCount(2, $tokens->all());

        $contact = $this->app->make(ContactRepository::class)->skipPresenter()->create([
            'mobile' => '09173011987',
            'handle' => "lbhurtado"
        ]);

        $object = $tokens->claim($contact, $claim_code);

        $this->assertInstanceOf(Group::class, $object);
        $this->assertEquals($group1->id, $object->id);
        $this->assertCount(1, $tokens->all());

        $token = $tokens->first();
        $this->assertEquals($multiple_usage_code, $token->code);

        $tokens->claim($contact, $multiple_usage_code);
        $tokens->claim($contact, $multiple_usage_code);
        $tokens->claim($contact, $multiple_usage_code);
        $tokens->claim($contact, $multiple_usage_code);
        $tokens->claim($contact, $multiple_usage_code);
        $this->assertCount(1, $tokens->all());

    }

    /** @test */
    function tokens_can_be_generated_from_an_object()
    {
        $group = factory(Group::class)->create();

        $code = 'GRP1';
        $token = Token::generate($group, $code);
        $this->assertEquals(Group::class, $token->class);
        $this->assertEquals($group->id, $token->reference);
        $this->assertEquals($code, $token->code);
    }
    /** @test */
    function tokens_can_be_auto_generated()
    {
        factory(Group::class,2)->create();

        $tokens = $this->app->make(TokenRepository::class)->skipPresenter();
        $groups = $this->app->make(GroupRepository::class)->skipPresenter();
        $tokens->generate($groups->all());

        $this->assertCount(count($groups->all()), $tokens->all());
    }

    /** @test */
    function tokens_can_be_claimed()
    {
        $contact = factory(Contact::class)->create();
        $group1 = factory(Group::class)->create();
        $group2 = factory(Group::class)->create();
        $token1 = Token::generateOneTime($group1);
        $token2 = Token::generate($group2);

        $tokens = $this->app->make(TokenRepository::class)->skipPresenter();

        $this->assertCount(2, $tokens->all());

        $object1 = $tokens->claim($contact, $token1->code);
        $this->assertCount(1, $tokens->all());

        $this->assertEquals($object1->attributesToArray(), $group1->attributesToArray());

        $name = $tokens->claim($contact, $token2->code, function($object) {
            return $object->name;
        });

        $this->assertCount(1, $tokens->all());
        $this->assertEquals($name, $group2->name);

    }
}
