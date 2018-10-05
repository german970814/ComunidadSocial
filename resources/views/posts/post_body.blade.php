 <div class="timeline">
    <div class="well" id="post-{{ $post->id }}" data-post-id="{{ $post->id }}">
        <div class="media" {!! (!isset($alone) || !$alone) ? sprintf('style="cursor: pointer;" ondblclick="window.location.href = \'%s\'"', route('post.show', $post->id)) : '' !!}>
            <div class="media-left">
                <a href="{{ route('usuario.show', $post->autor->id) }}">
                    <img src="{{ $post->autor->get_profile_photo_url() }}" alt="profile" width="32" height="32" class="media-object" />
                </a>
            </div>
            <div class="media-body">
                <a {!! isset($alone) && $alone ? 'style="display: inline-block"' : '' !!} href="{{ route('usuario.show', $post->autor->id) }}">
                    <h4 class="media-heading">{{ $post->autor->get_full_name() }}</h4>
                </a>
                @if ((isset($alone) && $alone) && ($post->autor->id !== $post->usuario_destino->id))
                <span class="color-3">></span>
                <a {!! isset($alone) && $alone ? 'style="display: inline-block"' : '' !!} href="{{ route('usuario.show', $post->usuario_destino->id) }}">
                    <h4 class="media-heading">{{ $post->usuario_destino->get_full_name() }}</h4>
                </a>
                @endif
                <span {!! isset($alone) && $alone ? 'style="display: block"' : '' !!}>{{ $post->created_at }}</span>
            </div>
        </div>
        @if ($post->photo && $post->get_photo_url())
        <div class="row post-media">
            <div class="media">
                <img src="{{ $post->get_photo_url() }}" alt="post-photo" class="img-preview img-responsive">
            </div>
        </div>
        @endif
        <div class="row post-content">
            <div class="post">
                <p>{{ $post->mensaje }}</p>
            </div>
        </div>
        <div class="row post-content">
            <div class="post-actions">
                @if ($post->likes() >= 1)
                <span>Le gusta a {{ $post->likes() }} persona{{ $post->likes() > 1 ? 's' : '' }}</span>
                @endif
                <button data-original-title="Me gusta esta publicación" data-placement="left" data-toggle="tooltip" class="like btn-social {{ Auth::guard()->user()->usuario->likes_post($post) ? 'btn-liked' : 'btn-like' }} btn-circle"><i class="fa fa-thumbs-up"></i></button>
            </div>
        </div>
        <div class="space-25"></div>
        <div class="row row-comment">
            <h4>Comentarios</h4>
            <dl class="comment-list">
                @foreach($post->comentarios->all() as $comentario)
                <dd>
                    <div id="comment-{{ $comentario->id }}" class="comment row-comment">
                        <div class="media" {!! (!isset($alone) || !$alone) ? sprintf('style="cursor: pointer;" ondblclick="window.location.href = \'%s\'"', $comentario->get_url()) : '' !!}>
                            <div class="media-left">
                                <a href="{{ route('usuario.show', $comentario->usuario->id) }}">
                                    <img src="{{ $comentario->usuario->get_profile_photo_url() }}" alt="profile" width="32" height="32" class="media-object" />
                                </a>
                            </div>
                            <div class="media-body">
                                <a href="{{ route('usuario.show', $comentario->usuario->id) }}">
                                    <h5 class="media-heading">{{ $comentario->usuario->get_full_name() }}</h5>
                                </a>
                                <span>{{ $comentario->created_at }}</span>
                            </div>
                            <div class="row post-content">
                                <div class="post">
                                    <p>{{ $comentario->mensaje }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </dd>
                @endforeach
            </dl>
        </div>
        <div class="row row-comment homeContactContent">
            <div class="form-group">
                <textarea name="mensaje" class="comment form-control border-color-4" placeholder="Escribe un comentario" rows="10" cols="50"></textarea>
            </div>
        </div>
    </div>
</div>