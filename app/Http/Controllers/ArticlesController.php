<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ArticlesController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['index', 'show']]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$article = new \App\Article;
		return view('articles.create', compact('article'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	//public function destroy($id)
	public function destroy(\App\Article $article)
	{
		$this->authorize('delete', $article);

		$article->delete();
		//return response()->json([], 204);
		flash()->success('forum.deleted');
		return redirect(route('articles.index'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	//public function edit($id)
	public function edit(\App\Article $article)
	{
		$this->authorize('update', $article);

		return view('articles.edit', compact('article'));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	//public function index()
	//public function index($slug = null)
	public function index(Request $request, $slug = null)
	{
		//$articles = \App\Article::get();
		//$articles = \App\Article::with('user')->get();
		//$articles = \App\Article::latest()->paginate(5);
		$query = $slug ? \App\Tag::whereSlug($slug)->firstOrFail()->articles() : new \App\Article;

		$query = $query->orderBy(
			$request->input('sort', 'created_at'),
			$request->input('order', 'desc')
		);
		if($keyword = request()->input('q')) {
			$raw = 'MATCH(title,content) AGAINST(? IN BOOLEAN MODE)';
			$query = $query->whereRaw($raw, [$keyword]);
		}

		$articles = $query->latest()->paginate(5);
		$articles->load('user');

		//dd(view('articles.index', compact('articles'))->render());
		return view('articles.index', compact('articles'));
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
    	//$article = auth()->user()->articles()->create($request->all());
    	$payload = array_merge($request->all(), [
    		'notification' => $request->has('notification'),
		]);
    	$article = $request->user()->articles()->create($payload);

    	if(!($article)) {
    		$article->tags()->sync($request->input('tags'));
    		return back()->with('flash_message', '글이 저장되지 않았습니다.')->withInput();
		}
		$article->tags()->sync($request->input('tags'));

		if($request->hasFile('files')) {
			$files = $request->file('files');
			foreach ($files as $file) {
				$filename = str_random() . filter_var($file->getClientOriginalName(), FILTER_SANITIZE_URL);
				$file->move(attachments_path(), $filename);

				$article->attachments()->create([
					'filename' => $filename,
					'bytes' => $file->getSize(),
					'mime' => $file->getClientMimeType()
				]);
			}
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
    //public function show($id) //explicit route model binding in RouteServiceProvider.php
    public function show(Article $article)
    {
		//$article = \App\Article::findOrFail($id); //route model binding
		////dd($article);
		////debug($article->toArray());
		//return view('articles.show', compact('article'));

		$article->view_count += 1;
		$article->save();

		$comments = $article->comments()->with('replies')->whereNull('parent_id')->latest()->get();
		return view('articles.show', compact('article', 'comments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function update(Request $request, $id)
	public function update(\App\Http\Requests\ArticleRequest $request, \App\Article $article)
    {
    	$article->update($request->all());
    	$article->tags()->sync($request->input('tags'));
    	flash()->success('수정하신 내용을 저장했습니다.');
    	return redirect(route('articles.show', $article->id));
    }
}
