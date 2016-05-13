<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PendingCreateRequest;
use App\Http\Requests\PendingUpdateRequest;
use App\Repositories\PendingRepository;
use App\Validators\PendingValidator;


class PendingsController extends Controller
{

    /**
     * @var PendingRepository
     */
    protected $repository;

    /**
     * @var PendingValidator
     */
    protected $validator;


    public function __construct(PendingRepository $repository, PendingValidator $validator)
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
        $pendings = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $pendings,
            ]);
        }

        return view('pendings.index', compact('pendings'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('pendings.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  PendingCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PendingCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $pending = $this->repository->create($request->all());

            $response = [
                'message' => 'Pending created.',
                'data'    => $pending->toArray(),
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
        $pending = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $pending,
            ]);
        }

        return view('pendings.show', compact('pending'));
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

        $pending = $this->repository->find($id);

        return view('pendings.edit', compact('pending'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  PendingUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(PendingUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $pending = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Pending updated.',
                'data'    => $pending->toArray(),
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
                'message' => 'Pending deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Pending deleted.');
    }
}
