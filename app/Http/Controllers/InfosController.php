<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\InfoCreateRequest;
use App\Http\Requests\InfoUpdateRequest;
use App\Repositories\InfoRepository;
use App\Validators\InfoValidator;


class InfosController extends Controller
{

    /**
     * @var InfoRepository
     */
    protected $repository;

    /**
     * @var InfoValidator
     */
    protected $validator;


    public function __construct(InfoRepository $repository, InfoValidator $validator)
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
        $infos = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $infos,
            ]);
        }

        return view('infos.index', compact('infos'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('infos.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  InfoCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(InfoCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $info = $this->repository->create($request->all());

            $response = [
                'message' => 'Info created.',
                'data'    => $info->toArray(),
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
        $info = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $info,
            ]);
        }

        return view('infos.show', compact('info'));
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

        $info = $this->repository->find($id);

        return view('infos.edit', compact('info'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  InfoUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(InfoUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $info = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Info updated.',
                'data'    => $info->toArray(),
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
                'message' => 'Info deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Info deleted.');
    }
}
