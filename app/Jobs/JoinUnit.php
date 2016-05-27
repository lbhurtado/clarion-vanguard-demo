<?php

namespace App\Jobs;

use Prettus\Repository\Contracts\RepositoryInterface;
use App\Repositories\ContactRepository;
use Illuminate\Database\Eloquent\Model;
use App\Entities\Contact;
use App\Mobile;

class JoinUnit extends Job
{
    protected $column;

    protected $keyword;
    protected $mobile;
    protected $handle;
    protected $handleModified = false;

    /**
     * JoinGroup constructor.
     * @param $attributes
     */
    public function __construct($attributes)
    {
        dd($attributes);
        foreach($attributes as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @param ContactRepository $contactRepository
     * @param RepositoryInterface $unitRepository
     * @return array
     */
    protected function getProspectAndUnit(ContactRepository $contactRepository, RepositoryInterface $unitRepository)
    {
        $prospect = $contactRepository->findByField('mobile', Mobile::number($this->mobile))->first();
        $unit = $unitRepository->findByField($this->column, $this->keyword)->first();

        return array($prospect, $unit);
    }

    /**
     * @param Contact $contact
     * @param $handle
     */
    protected function updateHandleOfContact(Contact $contact, $handle)
    {
        if (!is_null($handle)) {
            if ($contact->handle != $handle)
            {
                $contact->handle = $handle;
                $contact->save();
                $this->handleModified = true;
            }
        }
    }

    /**
     * Send the event once the prospect
     * is joined to the unit.
     *
     * @param $unit
     * @param $prospect
     */
    protected function joinUnit(Model $unit, Contact $prospect)
    {
        $contact = $unit->contacts()->where('contact_id', $prospect->id)->first();
        $prospectDoesNotBelongYet = is_null($contact);
        if ($prospectDoesNotBelongYet) {
            $unit->contacts()->attach($prospect);
            $unit->save();
            event((new \ReflectionClass($this->event))->newInstanceArgs(array($unit, $prospect, false)));
        }
        elseif ($this->handleModified)
        {
            event((new \ReflectionClass($this->event))->newInstanceArgs(array($unit, $prospect, true)));
        }
    }
}
