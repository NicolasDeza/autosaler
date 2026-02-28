<script setup lang="ts">
import heroImage from '@assets/images/hero.jpg'
</script>

<template>
  <section class="relative w-full h-[80vh] sm:h-170 min-h-125 overflow-hidden bg-black">

    <!-- Image de fond -->
    <img
      :src="heroImage"
      alt="Voiture de sport en exposition"
      class="absolute inset-0 w-full h-full object-cover scale-105"
    />

    <!-- Overlay gradient -->
    <div class="absolute inset-0"></div>


    <!-- Contenu -->
    <div class="relative z-10 h-full flex flex-col justify-center px-6 md:px-12 lg:px-20 max-w-7xl mx-auto">


      <!-- Titre -->
      <h1 class="text-5xl md:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight mb-5 max-w-3xl">
        Trouvez votre<br/>
        <span class="text-transparent bg-clip-text bg-linear-to-r from-[#ec232c] to-[#ff6b6b]">
          prochaine voiture
        </span>
      </h1>

      <!-- Sous-titre -->
      <p class="text-base md:text-lg text-white/65 max-w-lg mb-10 leading-relaxed font-light">
        Achetez des véhicules vérifiés auprès de professionnels partout en Belgique.
      </p>

      <!-- Stats -->
      <div class="flex items-center gap-8">
        <div class="text-center">
          <div class="text-2xl font-black text-white">1000+</div>
          <div class="text-xs text-white/50 uppercase tracking-wider mt-0.5">Annonces</div>
        </div>
        <div class="w-px h-8 bg-white/20"></div>
        <div class="text-center">
          <div class="text-2xl font-black text-white">150+</div>
          <div class="text-xs text-white/50 uppercase tracking-wider mt-0.5">Vendeurs</div>
        </div>
        <div class="w-px h-8 bg-white/20"></div>
        <div class="text-center">
          <div class="text-2xl font-black text-white">98%</div>
          <div class="text-xs text-white/50 uppercase tracking-wider mt-0.5">Satisfaits</div>
        </div>
      </div>

    </div>

  </section>
</template>
