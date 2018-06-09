<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use App\Http\Requests\CommentsRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommentController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //public function store(Request $request)
	public function store(CommentsRequest $request, Article $article)
    {
		$comment = $article->comments()->create(array_merge($request->all(), ['user_id' => $request->user()->id]));
		flash()->success('작성하신 댓글을 저장했습니다.');
		return redirect(route('articles.show', $article->id) . '#comment_' . $comment->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function show($id)
	public function show(\App\Article $article)
    {
    	$comments = $article->comments()->with('replies')->whereNull('parent_id')->latest()->get();
    	return view('articles.show', compact('article', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function update(Request $request, $id)
	public function update(CommentsRequest $request, Comment $comment)
    {
        $comment->update($request->all());
        return redirect(route('articles.show', $comment->commentable->id) . '#comment_' . $comment->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function destroy($id)
	public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json([], 204);
    }

    public function vote(Request $request, Comment $comment)
	{
		$this->validate($request, ['vote' => 'required|in:up,down']);
		if($comment->votes()->whereUserId($request->user()->id)->exists()) {
			return response()->json(['error' => 'already_voted'], 409);
		}
		$up = $request->input('vote') == 'up' ? true : false;
		$comment->votes()->create([
			'user_id' => $request->user()->id,
			'up' => $up,
			'down' => !($up),
			'voted_at' => Carbon::now()->toDateTimeString(),
		]);
		return response()->json([
			'voted' => $request->input('vote'),
			'value' => $comment->votes()->sum($request->input('vote')),
		]);
	}
}
