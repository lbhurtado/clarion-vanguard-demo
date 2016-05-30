<?php

namespace App\Jobs;

use Prettus\Repository\Contracts\RepositoryInterface;
use App\Repositories\ContactRepository;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Contact;
use App\Mobile;

class JoinUnit extends Job
{
    protected $attributes;

    protected $event;

    protected $contacts;

    protected $units;

    protected $mappings = [
        'fields' =>
            [
                'contact' => 'mobile',
                'unit'    => 'name',
                'handle'  => 'handle'
            ],
        'values' =>
            [
                'token'  => 'token',
                'mobile' => 'mobile',
                'handle' => 'handle'
            ]
    ];

    protected $handleModified = false;

    /**
     * @param $attributes
     * @throws \Exception
     */
    public function __construct($attributes)
    {
        if (is_null($this->event))
        {
            throw new \Exception('$this->event should have a value!');
        }

        $this->attributes = $attributes;
    }

    /**
     * @param ContactRepository $contacts
     * @return mixed
     * @throws \Exception
     * @deprecated
     */
    protected function getProspect(ContactRepository $contacts)
    {
        $this->contacts = $contacts->skipPresenter(); //this is needed to make sure the tests will not break

        $field = $this->mappings['fields']['contact'];
        $value = Mobile::number($this->attributes[$this->mappings['values']['mobile']]);
        $contact = $contacts->findByField($field, $value)->first();
        if (is_null($contact))
            throw new \Exception("Contact with {$value} does not exists!");

        return $contact;
    }

    protected function setupContacts(ContactRepository $contacts, &$prospect)
    {
        $this->contacts = $contacts->skipPresenter();

        $field = $this->mappings['fields']['contact'];
        $value = Mobile::number($this->attributes[$this->mappings['values']['mobile']]);
        $prospect = $contacts->findByField($field, $value)->first();
        if (is_null($prospect))
            throw new \Exception("Contact with {$value} does not exists!");

    }

    /**
     * @param RepositoryInterface $units
     * @return mixed
     * @throws \Exception
     * @deprecated
     */
    protected function getUnit(RepositoryInterface $units)
    {
        $field = $this->mappings['fields']['unit'];
        $value = $this->attributes[$this->mappings['values']['token']];
        $unit = $units->findByField($field, $value)->first();
        if (is_null($unit))
            throw new \Exception("Unit with {$value} does not exists!");

        return $units->findByField($field, $value)->first();
    }

    protected function setupUnits(RepositoryInterface $units, &$unit)
    {
        $this->units = $units;
        $field = $this->mappings['fields']['unit'];
        $value = $this->attributes[$this->mappings['values']['token']];
        $unit = $units->findByField($field, $value)->first();
        if (is_null($unit))
            throw new \Exception("Unit with {$value} does not exists!");
    }

    /**
     * @param Contact $contact
     * @param $handle
     */
    protected function updateHandle(Contact $contact, $handle)
    {
        $mobile = $contact->mobile;
        $this->contacts->update(compact('mobile', 'handle'), $contact->id);
    }

    /**
     * Send the event once the prospect
     * is attached to the unit.
     *
     * @param $unit
     * @param $prospect
     * @return void
     */
    protected function joinUnit(Model $unit, Contact $prospect)
    {
        $class = new \ReflectionClass($this->event);
        if ($this->prospectDoesNotBelongYet($unit, $prospect)) {
            $unit->contacts()->attach($prospect);
            $unit->save();
            event($class->newInstanceArgs(array($unit, $prospect, true)));
        }
        elseif ($this->handleModified)
        {
            event($class->newInstanceArgs(array($unit, $prospect, false)));
        }
    }

    /**
     * Refactored sentinel.
     *
     * @param Model $unit
     * @param Contact $prospect
     * @return bool
     */
    private function prospectDoesNotBelongYet(Model $unit, Contact $prospect)
    {
        $contact = $unit->contacts()->where('contact_id', $prospect->id)->first();

        return is_null($contact);
    }
}
