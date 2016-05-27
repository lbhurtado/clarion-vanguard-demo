<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\SubscriptionCreateRequest;
use App\Http\Requests\SubscriptionUpdateRequest;
use App\Repositories\SubscriptionRepository;
use App\Validators\SubscriptionValidator;


class SubscriptionsController extends Controller
{

    /**
     * @var SubscriptionRepository
     */
    protected $repository;

    /**
     * @var SubscriptionValidator
     */
    protected $validator;


    public function __construct(SubscriptionRepository $repository, SubscriptionValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $subscriptions = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $subscriptions,
            ]);
        }

        return view('subscriptions.index', compact('subscriptions'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('subscriptions.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  SubscriptionCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriptionCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $subscription = $this->repository->create($request->all());

            $response = [
                'message' => 'Subscription created.',
                'data'    => $subscription->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subscription = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $subscription,
            ]);
        }

        return view('subscriptions.show', compact('subscription'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $subscription = $this->repository->find($id);

        return view('subscriptions.edit', compact('subscription'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  SubscriptionUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(SubscriptionUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $subscription = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Subscription updated.',
                'data'    => $subscription->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Subscription deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Subscription deleted.');
    }
}
