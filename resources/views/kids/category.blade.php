@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #0cc0df;
        color: white;
    }

    .category-wrapper {
        padding: 2rem;
        text-align: center;
        min-height: 100vh;
        position: relative;
    }

    .category-title {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .back-button {
    background-color: #ffffff;
    color: #0cc0df;
    padding: 0.5rem 1.2rem;
    font-weight: bold;
    border-radius: 1rem;
    text-decoration: none;
    transition: background 0.2s;
    display: inline-block;

    position: absolute;
    top: 1.5rem;
    left: 1.5rem;
    z-index: 10;
    }


    .back-button:hover {
        background-color: #e0f9fc;
    }

    .content-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.5rem;
        max-width: 900px;
        margin: 0 auto;
    }

    .content-card {
        background: white;
        padding: 1rem;
        border-radius: 1rem;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        text-align: center;
        color: #333;
        transition: 0.3s ease;
        position: relative;
    }

    .content-card:hover {
        transform: scale(1.05);
    }

    .content-card img,
    .content-card video {
        max-width: 100%;
        height: 130px;
        object-fit: cover;
        border-radius: 0.8rem;
        margin-bottom: 0.5rem;
    }

    .content-card audio {
        width: 100%;
        margin-top: 0.5rem;
    }

    .content-title {
        font-weight: bold;
        margin-top: 0.5rem;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid white;
        border-top: 4px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 2rem auto;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .admin-controls {
        margin-top: 0.5rem;
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .admin-controls form,
    .admin-controls a {
        font-size: 0.8rem;
        color: #fff;
        background-color: #0cc0df;
        padding: 4px 8px;
        border-radius: 0.5rem;
        text-decoration: none;
    }

    .admin-controls form:hover,
    .admin-controls a:hover {
        background-color: #09a2b7;
    }
</style>

<div class="category-wrapper">
    <h1 class="category-title">{{ strtoupper($category) }}</h1>

    <a href="{{ route('kids.home') }}" class="back-button">← Back to Categories</a>

    <!-- Spinner -->
    <div id="spinner" class="spinner"></div>

    <!-- Content Grid -->
    <div class="content-grid" style="display: none;" id="contentGrid">
        @forelse($contents as $item)
            <div class="content-card">
                @if($item->media_type === 'image')
                    <img src="{{ asset('storage/' . $item->media_path) }}" alt="{{ $item->title }}">
                @elseif($item->media_type === 'audio')
                    <img src="{{ asset('images/kids/music-icon.png') }}" alt="Audio">
                    <audio controls>
                        <source src="{{ asset('storage/' . $item->media_path) }}">
                    </audio>
                @elseif($item->media_type === 'video')
                    <video controls>
                        <source src="{{ asset('storage/' . $item->media_path) }}">
                    </video>
                @endif

                <div class="content-title">{{ $item->title }}</div>

                @auth
                    @if(Auth::user()->role === 'tutor')
                        <div class="admin-controls">
                            <a href="{{ route('contents.edit', $item->id) }}">Edit</a>
                            <form action="{{ route('contents.destroy', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        @empty
            <p>No content available yet in this category.</p>
        @endforelse
    </div>
</div>

<!-- Simple JS to delay spinner -->
<script>
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.getElementById('spinner').style.display = 'none';
            document.getElementById('contentGrid').style.display = 'grid';
        }, 1000); // Spinner shown for 1 second
    });
</script>
@endsection
