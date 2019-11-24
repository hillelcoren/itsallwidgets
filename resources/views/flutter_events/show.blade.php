@extends('master')

@section('title', $event->event_name)
@section('description', $event->text_description)
@section('image_url', $event->image_url ? url($event->image_url) : '')

@section('header_title', 'A Searchable List of Flutter Resources')
@section('header_button_url', false)

@section('header_subtitle')
    Resources are sourced from the <a href="https://flutterweekly.net" target="_blank">Flutter Weekly Newsletter</a>
@endsection

@section('content')

    <section class="section is-body-font">
		<div class="container">

			<div class="columns">
                <!--
				<div class="column is-4 is-elevated">
					<img id="appImage" src="{{ $event->image_url }}"/>
				</div>
                -->
                <div class="column is-2"></div>
				<div class="column is-8">
					<nav class="breadcrumb" aria-label="breadcrumbs">
						<ul>
							<li><a href="{{ feUrl() }}">All Events</a></li>
							<li class="is-active"><a href="#" aria-current="page">{{ $event->event_name }}</a></li>
						</ul>
					</nav>

					<div class="content">
						<h2 class="title">
                            <div>
    							{{ $event->event_name }}

                                &nbsp;&nbsp;

                                <span class="button is-outlined is-small is-static">
                                    Event
                                </span>
                            </div>

							<div style="border-bottom: 2px #368cd5 solid; width: 50px; padding-top:12px;"/>
						</h2>

                        <div class="content">
                            <a href="{{ url($event->event_url) }}" target="_blank" rel="nofollow" style="font-size:20px">
                                {{ url($event->event_url) }}
                            </a><br/>
                        </div>

						<div class="content">
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
									<a href="https://twitter.com/share?text={{ urlencode($event->title) }}&amp;url={{ urlencode(url('/flutter-app/' . $event->slug)) }}" target="_blank" rel="nofollow">
										<div class="dropdown-content">
											<div class="dropdown-item">
												<i style="font-size: 20px" class="fab fa-twitter"></i> &nbsp; Twitter
											</div>
										</div>
									</a>
								</div>
							</div>

							<br/>&nbsp;<br/>

                            <div class="block">
								{!! nl2br(e($event->text_description)) !!}
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

@endsection
