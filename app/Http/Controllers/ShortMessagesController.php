<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ShortMessageCreateRequest;
use App\Http\Requests\ShortMessageUpdateRequest;
use App\Repositories\ShortMessageRepository;
use App\Validators\ShortMessageValidator;


class ShortMessagesController extends Controller
{

    /**
     * @var ShortMessageRepository
     */
    protected $repository;

    /**
     * @var ShortMessageValidator
     */
    protected $validator;


    public function __construct(ShortMessageRepository $repository, ShortMessageValidator $validator)
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
        $shortMessages = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $shortMessages,
            ]);
        }

        return view('shortMessages.index', compact('shortMessages'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('shortMessages.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  ShortMessageCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ShortMessageCreateRequest $request)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $shortMessage = $this->repository->create($request->all());

            $response = [
                'message' => 'ShortMessage created.',
                'data'    => $shortMessage->toArray(),
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
        $shortMessage = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $shortMessage,
            ]);
        }

        return view('shortMessages.show', compact('shortMessage'));
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

        $shortMessage = $this->repository->find($id);

        return view('shortMessages.edit', compact('shortMessage'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ShortMessageUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(ShortMessageUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $shortMessage = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'ShortMessage updated.',
                'data'    => $shortMessage->toArray(),
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
                'message' => 'ShortMessage deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'ShortMessage deleted.');
    }
}
