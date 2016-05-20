<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\WhitelistedNumberCreateRequest;
use App\Http\Requests\WhitelistedNumberUpdateRequest;
use App\Repositories\WhitelistedNumberRepository;
use App\Validators\WhitelistedNumberValidator;


class WhitelistedNumbersController extends Controller
{

    /**
     * @var WhitelistedNumberRepository
     */
    protected $repository;

    /**
     * @var WhitelistedNumberValidator
     */
    protected $validator;


    public function __construct(WhitelistedNumberRepository $repository, WhitelistedNumberValidator $validator)
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
        $whitelistedNumbers = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $whitelistedNumbers,
            ]);
        }

        return view('whitelistedNumbers.index', compact('whitelistedNumbers'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('whitelistedNumbers.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  WhitelistedNumberCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(WhitelistedNumberCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $whitelistedNumber = $this->repository->create($request->all());

            $response = [
                'message' => 'WhitelistedNumber created.',
                'data'    => $whitelistedNumber->toArray(),
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
        $whitelistedNumber = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $whitelistedNumber,
            ]);
        }

        return view('whitelistedNumbers.show', compact('whitelistedNumber'));
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

        $whitelistedNumber = $this->repository->find($id);

        return view('whitelistedNumbers.edit', compact('whitelistedNumber'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  WhitelistedNumberUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(WhitelistedNumberUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $whitelistedNumber = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'WhitelistedNumber updated.',
                'data'    => $whitelistedNumber->toArray(),
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
                'message' => 'WhitelistedNumber deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'WhitelistedNumber deleted.');
    }
}
