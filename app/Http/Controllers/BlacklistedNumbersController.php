<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\BlacklistedNumberCreateRequest;
use App\Http\Requests\BlacklistedNumberUpdateRequest;
use App\Repositories\BlacklistedNumberRepository;
use App\Validators\BlacklistedNumberValidator;


class BlacklistedNumbersController extends Controller
{

    /**
     * @var BlacklistedNumberRepository
     */
    protected $repository;

    /**
     * @var BlacklistedNumberValidator
     */
    protected $validator;


    public function __construct(BlacklistedNumberRepository $repository, BlacklistedNumberValidator $validator)
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
        $blacklistedNumbers = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $blacklistedNumbers,
            ]);
        }

        return view('blacklistedNumbers.index', compact('blacklistedNumbers'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('blacklistedNumbers.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  BlacklistedNumberCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BlacklistedNumberCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $blacklistedNumber = $this->repository->create($request->all());

            $response = [
                'message' => 'BlacklistedNumber created.',
                'data'    => $blacklistedNumber->toArray(),
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
        $blacklistedNumber = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $blacklistedNumber,
            ]);
        }

        return view('blacklistedNumbers.show', compact('blacklistedNumber'));
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

        $blacklistedNumber = $this->repository->find($id);

        return view('blacklistedNumbers.edit', compact('blacklistedNumber'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  BlacklistedNumberUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(BlacklistedNumberUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $blacklistedNumber = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'BlacklistedNumber updated.',
                'data'    => $blacklistedNumber->toArray(),
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
                'message' => 'BlacklistedNumber deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'BlacklistedNumber deleted.');
    }
}
