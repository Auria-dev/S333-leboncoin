@extends('layout')

@section('title', 'Notifications')

@section('content')

<div class="dashboard-container" style="max-width: 800px; margin: 0 auto;">
    <h1 class="section-title">Notifications</h1>

    <div class="flex-col" style="gap: 1rem; width: 100%;">
        @foreach($notifications as $notif)
            {{-- 
               FIX: Added 'flex: 1 0 auto' and 'width: 100%' to override the .res-card fixed dimensions.
               This ensures the height fits the content.
            --}}
            <div class="res-card @if(!$notif->read_at) unread @endif" 
                 style="flex: 1 0 auto; width: 100%; height: auto; flex-direction: row; align-items: center; padding: 1.5rem; justify-content: space-between; 
                 @if(!$notif->read_at) background-color: var(--primary-light); border-color: rgba(var(--primary-rgb), 0.3); @endif">
                
                <div style="flex: 1; padding-right: 1.5rem;">
                    <p style="color: var(--text-main); font-weight: 600; font-size: 1rem; margin-bottom: 0.5rem; line-height: 1.4; margin-top: 0;">
                        {{ $notif->data['message'] }}
                    </p>

                    @if($notif->data['link'])
                        <a href="{{ $notif->data['link'] }}" class="hyperlink" style="font-size: 0.9rem;">
                            Voir les détails &rarr;
                        </a>
                    @endif
                </div>

                @if(!$notif->read_at)
                    <form method="POST" action="{{ route('notifications.read', $notif->id) }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="other-btn" style="width: auto; padding: 0.5rem 1rem; font-size: 0.85rem; white-space: nowrap;">
                            Marquer comme lu
                        </button>
                    </form>
                @else
                    <span style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; white-space: nowrap;">
                        Lu
                    </span>
                @endif
            </div>
        @endforeach

        @if($notifications->isEmpty())
            <div class="search-empty">
                Aucune nouvelle notification.
            </div>
        @endif
    </div>
</div>

@endsection