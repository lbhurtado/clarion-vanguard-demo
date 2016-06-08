<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\BroadcastCreateRequest;
use App\Http\Requests\BroadcastUpdateRequest;
use App\Repositories\BroadcastRepository;
use App\Validators\BroadcastValidator;


class BroadcastsController extends Controller
{

    /**
     * @var BroadcastRepository
     */
    protected $repository;

    /**
     * @var BroadcastValidator
     */
    protected $validator;


    public function __construct(BroadcastRepository $repository, BroadcastValidator $validator)
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
        $broadcasts = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $broadcasts,
            ]);
        }

        return view('broadcasts.index', compact('broadcasts'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('broadcasts.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  BroadcastCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BroadcastCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $broadcast = $this->repository->create($request->all());

            $response = [
                'message' => 'Broadcast created.',
                'data'    => $broadcast->toArray(),
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
        $broadcast = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $broadcast,
            ]);
        }

        return view('broadcasts.show', compact('broadcast'));
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

        $broadcast = $this->repository->find($id);

        return view('broadcasts.edit', compact('broadcast'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  BroadcastUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(BroadcastUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $broadcast = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Broadcast updated.',
                'data'    => $broadcast->toArray(),
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
                'message' => 'Broadcast deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Broadcast deleted.');
    }
}
