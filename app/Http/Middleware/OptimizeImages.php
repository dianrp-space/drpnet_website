<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OptimizeImages
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof Response && strpos($response->headers->get('Content-Type'), 'text/html') !== false) {
            $content = $response->getContent();
            
            // Tambahkan lazy loading pada gambar
            $content = preg_replace('/<img(.*?)>/i', '<img$1 loading="lazy">', $content);
            
            // Tambahkan dimensi default jika tidak ada
            $content = preg_replace('/<img((?!width|height).*?)>/i', '<img$1 width="800" height="600">', $content);
            
            $response->setContent($content);
        }

        return $response;
    }
} 