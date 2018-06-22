@servers(['web' => 'root@ec17.ecfirm.net'])

@task('hello', ['on' => ['web']])
	HOSTNAME=$(hostname)
	echo "Hello Envoy! Responding from $HOSTNAME";
@endtask