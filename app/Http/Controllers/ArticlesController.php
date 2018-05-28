<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	//$articles = \App\Article::get();
    	//$articles = \App\Article::with('user')->get();
		$articles = \App\Article::latest()->paginate(5);
		$articles->load('user');

		//dd(view('articles.index', compact('articles'))->render());
    	return view('articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //public function store(Request $request)
    public function store(\App\Http\Requests\ArticleRequest $request)
    {
    	//dump($request);
    	/* $rules = [
    		'title' => ['required'],
			'content' => ['required', 'min:10'],
		]; */
    	/* $msg = [ //@deprecated
    		'title.required' => '제목은 필수 입력 항목입니다.',
			'content.required' => '본문은 필수 입력 항목입니다.',
			'content.min' => '본문은 최소 :min 글자 이상이 필요합니다.',
		];
    	$validator = Validator::make($request->all(), $rules, $msg);
    	if($validator->fails()) {
    		return back()->withErrors($validator)->withInput();
		} */
		//$this->validate($request, $rules);

		//$article = \App\User::find(1)->articles()->create($request->all());
    	$article = auth()->user()->articles()->create($request->all());
    	if(!($article)) {
    		return back()->with('flash_message', '글이 저장되지 않았습니다.')->withInput();
		}

    	//event('article.created', [$article]);
		//event(new \App\Events\ArticleCreated($article));
		//event(new \App\Events\ArticleCreatedT1($article));
		event(new \App\Events\ArticlesEvent($article));

		return redirect(route('articles.index'))->with('flash_message', '작성하신 글이 저장되었습니다.');
	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		$article = \App\Article::findOrFail($id);
		//dd($article);
		debug($article->toArray());
		return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	dump($id);
		echo __METHOD__;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    	dump($request);
    	dump($id);
		echo __METHOD__;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    	dump($id);
		echo __METHOD__;
    }
}
