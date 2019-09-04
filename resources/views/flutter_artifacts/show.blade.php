@extends('master')

@section('title', $artifact->title)
@section('description', $artifact->comment)
@section('image_url', $artifact->image_url ? url($artifact->image_url) : '')

@section('header_title', 'A Searchable List of Flutter Resources')
@section('header_button_url', false)

@section('header_subtitle')
    Resources are sourced from the <a href="https://flutterweekly.net" target="_blank">Flutter Weekly Newsletter</a>
@endsection

@section('content')

    <section class="section is-body-font">
		<div class="container">

			<div class="columns">
                @if ($artifact->image_url || $artifact->gif_url)
    				<div class="column is-4 is-elevated">
                        @if ($artifact->image_url)
		                   <img id="appImage" src="{{ $artifact->image_url }}" width="100%" style="padding-bottom:16px"/>
                        @endif
                        @if ($artifact->gif_url)
                            <img id="appGif" src="{{ $artifact->gif_url }}" width="100%"/>
                        @endif
    				</div>
                @else
                    <div class="column is-4"></div>
                @endif
				<div class="column is-8">
					<nav class="breadcrumb" aria-label="breadcrumbs">
						<ul>
							<li><a href="{{ fxUrl() }}">All Resources</a></li>
							<li class="is-active"><a href="#" aria-current="page">{{ $artifact->title }}</a></li>
						</ul>
					</nav>

					<div class="content">
						<h2 class="title">
                            <div>
    							{{ $artifact->title }}

                                &nbsp;&nbsp;

                                <span class="button is-outlined is-small is-static is-{{ $artifact->type }}">
                                    {{ $artifact->pretty_type }}
                                </span>
                            </div>

							<div style="border-bottom: 2px #368cd5 solid; width: 50px; padding-top:12px;"/>
						</h2>

                        <div class="content">
                            <a href="{{ url($artifact->url) }}" target="_blank" rel="nofollow" style="font-size:20px">
                                {{ url($artifact->url) }}
                            </a><br/>
                        </div>

						<div class="content">
							@if ($artifact->facebook_url)
								<a class="button is-slightly-elevated" href="{{ $artifact->facebook_url }}" target="_blank" rel="nofollow">
									<i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
								</a> &nbsp;
							@endif
							@if ($artifact->twitter_url)
								<a class="button is-slightly-elevated" 	href="{{ $artifact->twitter_url }}" target="_blank" rel="nofollow">
									<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
								</a> &nbsp;
							@endif
							@if ($artifact->instagram_url)
								<a class="button is-slightly-elevated" 	href="{{ $artifact->instagram_url }}" target="_blank" rel="nofollow">
									<i style="font-size: 20px" class="fab fa-instagram"></i> &nbsp; Instagram
								</a> &nbsp;
							@endif

							<div class="dropdown is-hoverable">
								<div class="dropdown-trigger is-slightly-elevated">
									<button class="button" aria-haspopup="true" aria-controls="dropdown-menu4">
										<span>
											<i style="font-size: 20px" class="fa fa-share"></i> &nbsp;
											Share Resource
										</span>
										<span class="icon is-small">
											<i class="fas fa-angle-down" aria-hidden="true"></i>
										</span>
									</button>
								</div>
								<div class="dropdown-menu" role="menu">
									<a href="https://www.facebook.com/sharer/sharer.php?u=#url" target="_blank" rel="nofollow">
										<div class="dropdown-content">
											<div class="dropdown-item">
												<i style="font-size: 20px" class="fab fa-facebook"></i> &nbsp; Facebook
											</div>
										</div>
									</a>
									<a href="https://twitter.com/share?text={{ urlencode($artifact->title) }}&amp;url={{ urlencode(url('/flutter-app/' . $artifact->slug)) }}" target="_blank" rel="nofollow">
										<div class="dropdown-content">
											<div class="dropdown-item">
												<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
											</div>
										</div>
									</a>
								</div>
							</div>

							<br/>&nbsp;<br/>

                            <div class="panel-block">
	                            <div class="block wrap">{!! nl2br(e($artifact->meta_description)) !!}</div>
							</div><br/>

                            <div>
                                @if ($artifact->authorUrl())
                                    <a href="{{ $artifact->authorUrl() }}" target="_blank">{{ $artifact->authorName() }}</a>
                                @elseif ($artifact->authorName())
                                    {{ $artifact->authorName() }}
                                @endif

                                @if ($artifact->hasAuthor() && $artifact->hasPublisher())
                                    •
                                @endif

                                @if ($artifact->publisherUrl())
                                    <a href="{{ $artifact->publisherUrl() }}" target="_blank">{{ '@' . $artifact->meta_publisher_twitter }}</a>
                                @elseif ($artifact->publisherName())
                                    {{ $artifact->publisherName() }}
                                @endif
                            </div>


                            <div style="padding-top:0px;padding-bottom:18px;">
                                <span style="padding-left:0px; padding-top:6px; color:#777; font-size:14px;">
                                    Published on {{ $artifact->pretty_published_date }}
                                </span>
                            </div>



						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

@endsection
