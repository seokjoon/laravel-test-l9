<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ClearOrphanedAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:coa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '고아가 된 첨부 파일을 청소합니다.';//'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	//$orphand = \App\Attachment::whereNull('article_id')->where('created_at', 'c', Carbon::now()->subWeek())->get();
		$orphand = \App\Attachment::whereNull('article_id')->where('created_at', '<', Carbon::now()->subWeek())->get();

    	Log::debug($orphand);

    	foreach ($orphand as $attachment) {
    		$path = attachments_path($attachment->filename);
    		File::delete($path);
    		$attachment->delete();
    		$this->line('삭제됨: ' . $path);
		}
		$this->info('고아가 된 파일을 전부 청소했습니다.');
    	return;
    }
}
