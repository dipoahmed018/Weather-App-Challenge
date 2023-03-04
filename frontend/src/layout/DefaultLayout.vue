<template>
    <main class="min-h-screen p-2 bg-gradient-to-b from-background2 to-background1">
        <div class="top-bar">
            <h1 class="title text-4xl text-white/70 text-center p-3">Weather Forecast</h1>
        </div>
        <div class="main">
            <slot />
        </div>
    </main>
</template>

<script setup lang="ts"></script>
