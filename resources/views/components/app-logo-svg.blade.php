@props(['size' => 'large'])

@php
    $dimensions = $size === 'large' ? 'h-12 w-12' : 'h-10 w-10';
    $uniqueId = 'logo-' . $size . '-' . uniqid();
@endphp

<div class="{{ $dimensions }} relative">
    <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
        <defs>
            <linearGradient id="{{ $uniqueId }}-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color: #3B82F6; stop-opacity: 1" />
                <stop offset="50%" style="stop-color: #8B5CF6; stop-opacity: 1" />
                <stop offset="100%" style="stop-color: #EC4899; stop-opacity: 1" />
            </linearGradient>
            <linearGradient id="{{ $uniqueId }}-book" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color: #FCD34D; stop-opacity: 1" />
                <stop offset="100%" style="stop-color: #F59E0B; stop-opacity: 1" />
            </linearGradient>
        </defs>
        <circle cx="50" cy="50" r="48" fill="url(#{{ $uniqueId }}-gradient)" />
        <circle cx="50" cy="50" r="42" fill="white" opacity="0.95" />
        <g transform="translate(50, 50)">
            <path d="M -20 -8 Q -20 -15, -12 -18 L -12 15 Q -20 12, -20 5 Z" fill="url(#{{ $uniqueId }}-book)" />
            <path d="M 20 -8 Q 20 -15, 12 -18 L 12 15 Q 20 12, 20 5 Z" fill="url(#{{ $uniqueId }}-book)" />
            <rect x="-2" y="-18" width="4" height="33" fill="#F59E0B" rx="1" />
            <circle cx="-15" cy="-22" r="2" fill="#FCD34D" opacity="0.8" />
            <circle cx="15" cy="-22" r="2" fill="#FCD34D" opacity="0.8" />
            <circle cx="0" cy="20" r="2.5" fill="#EC4899" opacity="0.8" />
            <g transform="translate(-25, 8)">
                <ellipse cx="0" cy="-3" rx="4" ry="5" fill="#FCD34D" opacity="0.9" />
                <rect x="-1.5" y="2" width="3" height="2" fill="#F59E0B" opacity="0.9" rx="0.5" />
            </g>
            <g transform="translate(25, 8)">
                <polygon points="0,-6 -5,-3 -5,-1 5,-1 5,-3" fill="#3B82F6" opacity="0.9" />
                <rect x="-6" y="-1" width="12" height="1.5" fill="#8B5CF6" opacity="0.9" />
            </g>
        </g>
        <circle cx="15" cy="50" r="1.5" fill="#EC4899" opacity="0.6" />
        <circle cx="85" cy="50" r="1.5" fill="#EC4899" opacity="0.6" />
    </svg>
</div>