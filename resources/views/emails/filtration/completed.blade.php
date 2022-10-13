<div>
@switch($result->type)
    @case(1)
	 The file you downloaded is a photo.
        @break

    @case(2)
	 The file you downloaded is a video.
        @break

    @default
	 The file you downloaded is a text.
@endswitch
</div>

<div>
@switch($result->status)	

    @case(false)
	 The file that you requested is rejected because it contains {{$result->reason}}
		@break

	 @default
	 It has been successfully moderated

@endswitch
</div>