<div class="page-header"> <h4>댓글</h4> </div>

<div class="form__new__comment">
	@if($currentUser) @include('comments.partial.create')
	@else @include('comments.partial.login')
	@endif
</div>

<div class="list__comment">
	@forelse($comments as $comment)
		@include('comments.partial.comment', [ 'parentId' => $comment->id, 'isReply' => false, ])
	@empty
	@endforelse
</div>

@section('script')
	@parent
	<script>
		jQuery(".btn__delete__comment").on("click", function(e) {
			var commentId = jQuery(this).closest(".item__comment").data("id");
			var articleId = jQuery('article').data('id');
			if(confirm("댓글을 삭제합니다.")) {
				jQuery.ajax({
					type: "POST",
					url: "/comments" + commentId,
					data: { _method: "DELETE" }
				}).then(function() {
					jQuery("#comment_" + commentId) . fadeOut(1000, function() { jQuery(this).remove(); })
				});
			}
		});
	</script>
@stop