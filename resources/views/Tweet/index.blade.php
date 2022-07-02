<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}"></script>
    <title>つぶやきアプリ</title>
</head>
<body>
    <h1>つぶやきアプリ</h1>
    @auth
        <div>
            <a href="{{ route('tweet.index') }}">戻る</a>
            <p>投稿フォーム</p>
        @if(session('feedback.success'))
            <p style="color: green">{{ session('feedback.success') }}</p>
        @endif
            <form action="{{ route('tweet.create') }}" method="POST">
                @csrf
                <label for="tweet-content">つぶやき</label>
                <span>140文字まで</span>
                <textarea id="tweet-content" type="text" name="tweet" placeholder="つぶやきを入力"></textarea>
                @error('tweet')
                    <p style="color: red;">{{ $message }}</p>
                @enderror
                <button type="submit">投稿</button>
            </form>
        </div>
    @endauth
    <h1>つぶやき一覧</h1>
        <div>
            @foreach($tweet as $tweet)
                <details>
                    <summary>{{ $tweet->content }} by {{ $tweet->user->name }}</summary>
                    @if(Auth::id() === $tweet->user_id)
                        <div>
                            <a href="{{ route('tweet.update.index',['tweetId' => $tweet->id]) }}">編集</a>
                            <form action="{{ route('tweet.delete',['tweetId' => $tweet->id]) }}" method="POST">
                                @method('delete')
                                @csrf
                                <button type="submit">削除</button>
                            </form>
                        </div>
                    @else
                        編集できません
                    @endif
                </details>
            @endforeach
        </div>
</body>
</html>
