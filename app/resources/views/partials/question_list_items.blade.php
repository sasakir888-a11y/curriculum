@forelse ($questions as $q)
    <!-- 🌟 カード全体をクリック可能に（cursor: pointerを追加、onclickで詳細ページへ遷移） -->
    <div class="card mb-3 question-item shadow-sm" 
         style="cursor: pointer;" 
         onclick="if(!event.target.closest('.bookmark-btn')) window.location='{{ url('/question/' . $q->id) }}';"> 
        <div class="card-body">
            <div class="row">
                <!-- 左側 -->
                <div class="col-md-8 d-flex flex-column">
                    
                    <!-- 🏷️ 【追加】ステータスバッジ ＆ 閲覧数 -->
                    <div class="mb-2 d-flex align-items-center gap-1 flex-wrap">
                        @if($q->status === 'open')
                            <span class="badge bg-success text-white">回答募集中</span>
                        @elseif($q->status === 'solved')
                            <span class="badge bg-primary text-white">解決済み</span>
                        @endif

                        <span class="badge bg-light text-dark border ms-1">
                            <i class="bi bi-eye"></i> {{ $q->view_count }} 閲覧
                        </span>
                    </div>

                    <h4>
                        <!-- 🌟 カードクリックと重複しないようリンクの装飾をリセット -->
                        <a href="{{ url('/question/' . $q->id) }}" class="text-decoration-none text-dark fw-bold">
                            {{ $q->title }}
                        </a>
                    </h4>
                    <p class="flex-grow-1 text-secondary">
                        {{ \Illuminate\Support\Str::limit($q->content, 120) }}
                    </p>
                    <div class="d-flex gap-3 align-items-center">
                        <div class="me-2 rounded-circle overflow-hidden"
                            style="width:35px; height:35px; background:#eee;
                                    display:flex; align-items:center; justify-content:center;">
                            @if($q->user && $q->user->profile_image)
                                <img src="{{ asset('storage/'.$q->user->profile_image) }}"
                                    style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <i class="bi bi-person-fill" style="font-size:20px; color:#aaa;"></i>
                            @endif
                        </div>
                        <p class="text-muted mb-0">
                            投稿者：{{ $q->user->name ?? '不明' }}
                            ｜ 投稿日：{{ $q->created_at->format('Y-m-d') }}
                        </p>
                        @auth
                        <!-- 🌟 ブックマークボタンだけはカードクリックのイベントと衝突しないようクラスで制御 -->
                        <button class="bookmark-btn border-0 bg-transparent p-0" data-id="{{ $q->id }}">
                            <i class="bi {{ ($q->isBookmarked ?? false) ? 'bi-bookmark-fill text-warning' : 'bi-bookmark text-secondary' }}"
                            style="font-size: 1.4rem;"></i>
                        </button>
                        @endauth
                    </div>
                </div>
                <!-- 右：画像 -->
                <div class="col-md-4 text-end">
                    @if($q->image_path)
                        <img src="{{ asset('storage/'.$q->image_path) }}"
                            class="img-fluid rounded"
                            style="max-height:150px; object-fit:cover;">
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    @if(!request()->ajax())
        <p>質問はまだありません</p>
    @endif
@endforelse