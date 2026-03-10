<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { Check, Send } from 'lucide-vue-next'
import { ref, computed } from 'vue'
import { toast } from 'vue-sonner'
import SubscriptionInquiryController from '@/actions/App/Http/Controllers/SubscriptionInquiryController'
import { Button } from '@/components/ui/button'
import { Card } from '@/components/ui/card'
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { useTranslation } from '@/composables/useTranslation'

const { __ } = useTranslation()

const plans = [10, 25, 50, 100]

const selected = ref(25)
const showModal = ref(false)

const price = computed(() => {
  switch (selected.value) {
    case 10: return 9.99
    case 25: return 19.99
    case 50: return 39.99
    case 100: return 79.99
    default: return 19.99
  }
})

const form = useForm({
  company_name: '',
  contact_name: '',
  email: '',
  phone: '',
  plan_listings: 25,
  message: '',
})

function openModal() {
  form.plan_listings = selected.value
  form.clearErrors()
  showModal.value = true
}

function submit() {
  form.post(SubscriptionInquiryController().url, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      form.reset()
      showModal.value = false
      toast.success(__('pricing.modal_success_title'), {
        description: __('pricing.modal_success_desc'),
      })
    },
  })
}

function closeModal() {
  showModal.value = false
}
</script>

<template>
  <section class="w-full pt-8 sm:pt-12 pb-16 sm:pb-24">
    <div class="max-w-5xl mx-auto px-6">

      <div class="text-center mb-16">
        <h2 class="text-3xl font-[1000] text-foreground tracking-tight uppercase ">
          {{ __('pricing.title_prefix') }}
          <span class="text-red-600 ">{{ __('pricing.title_highlight') }}</span>
        </h2>
        <p class="text-muted-foreground mt-4 max-w-lg mx-auto font-medium">
          {{ __('pricing.subtitle') }}
        </p>
      </div>

      <div class="flex justify-center mb-10">
        <div class="bg-muted border border-border rounded-lg px-5 py-2.5 text-sm text-muted-foreground flex items-center gap-3 shadow-sm">
          <span class="bg-red-500 text-white font-black uppercase text-[9px] px-2 py-0.5 rounded-md tracking-widest">
            Pro
          </span>
          <span class="font-semibold">{{ __('pricing.pro_only_description') }}</span>
        </div>
      </div>

      <div class="max-w-2xl mx-auto relative">
        <div class="bg-muted/80 p-1.5 rounded-lg grid grid-cols-2 sm:grid-cols-4 gap-1 mb-8 border border-border/50">
          <button
            v-for="plan in plans"
            :key="plan"
            @click="selected = plan"
            class="flex-1 py-3 px-2 rounded-xl text-sm font-bold transition-all duration-300 relative cursor-pointer"
            :class="selected === plan
              ? 'bg-card text-foreground shadow-md scale-[1.02]'
              : 'text-muted-foreground hover:text-foreground hover:bg-card/40'"
          >
            {{ plan }}
            <span class="hidden sm:inline-block ml-1 opacity-60 font-medium">{{ __('pricing.listings') }}</span>
          </button>
        </div>

        <Card class="relative z-10 overflow-hidden rounded-lg bg-card border-border p-8 md:p-12 transition-all duration-500">

          <div class="flex justify-center mb-8">
            <div class="h-6 flex items-center">
              <span v-if="selected >= 50" class="bg-red-50 text-red-600 text-[10px] font-black uppercase px-4 py-1.5 rounded-full border border-red-100 animate-pulse">
                {{ __('pricing.badge_premium') }}
              </span>
              <span v-else class="text-[10px] font-black uppercase text-muted-foreground tracking-[0.2em]">
                {{ __('pricing.badge_flexible') }}
              </span>
            </div>
          </div>

          <div class="flex flex-col items-center">
            <div class="flex flex-col sm:flex-row items-center gap-10 w-full mb-10">
              <div class="flex flex-col items-center sm:items-start flex-1">
                <div class="flex items-start gap-1">
                  <transition name="price-pop" mode="out-in">
                    <span :key="price" class="text-6xl font-[1000] text-foreground tracking-tighter tabular-nums">
                      {{ price }}
                    </span>
                  </transition>
                  <span class="text-2xl font-bold text-red-600 mt-3">€</span>
                </div>
                <p class="text-muted-foreground font-bold text-xs uppercase tracking-tight mt-1">
                  par {{ __('pricing.month') }} — {{ __('pricing.no_commitment') }}
                </p>
              </div>

              <div class="hidden sm:block w-px h-20 bg-linear-to-b from-transparent via-border to-transparent"></div>

              <div class="space-y-4 flex-1">
                <div v-for="feature in ['photos', 'stats']" :key="feature" class="flex items-center gap-3 group">
                  <div class="shrink-0 bg-emerald-100 text-emerald-600 rounded-full p-1 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                    <Check :size="14" stroke-width="4" />
                  </div>
                  <span class="text-card-foreground text-sm font-bold group-hover:text-foreground transition-colors">
                    {{ __(`pricing.feature_${feature}`) }}
                  </span>
                </div>
              </div>
            </div>

            <Button @click="openModal" class="w-full h-12 rounded-lg bg-red-500 hover:bg-red-600 hover:scale-[1.06] text-white text-base font-black transition-all duration-300 cursor-pointer">
              {{ __('pricing.cta_paid') }}
            </Button>
          </div>
        </Card>
      </div>
    </div>

    <!-- Modal de demande d'abonnement -->
    <Dialog :open="showModal" @update:open="closeModal">
      <DialogContent class="sm:max-w-lg">
          <DialogHeader>
            <DialogTitle>{{ __('pricing.modal_title') }}</DialogTitle>
            <DialogDescription>
              {{ __('pricing.modal_description', { count: form.plan_listings, price: price }) }}
            </DialogDescription>
          </DialogHeader>

          <form @submit.prevent="submit" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-2">
                <Label for="company_name">{{ __('pricing.field_company') }}</Label>
                <Input id="company_name" v-model="form.company_name" :placeholder="__('pricing.placeholder_company')" />
                <p v-if="form.errors.company_name" class="text-sm text-red-500">{{ form.errors.company_name }}</p>
              </div>
              <div class="space-y-2">
                <Label for="contact_name">{{ __('pricing.field_contact') }}</Label>
                <Input id="contact_name" v-model="form.contact_name" :placeholder="__('pricing.placeholder_contact')" />
                <p v-if="form.errors.contact_name" class="text-sm text-red-500">{{ form.errors.contact_name }}</p>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="space-y-2">
                <Label for="email">{{ __('pricing.field_email') }}</Label>
                <Input id="email" type="email" v-model="form.email" placeholder="pro@garage.be" />
                <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>
              </div>
              <div class="space-y-2">
                <Label for="phone">{{ __('pricing.field_phone') }}</Label>
                <Input id="phone" type="tel" v-model="form.phone" placeholder="+32 4XX XXX XXX" />
                <p v-if="form.errors.phone" class="text-sm text-red-500">{{ form.errors.phone }}</p>
              </div>
            </div>

            <div class="space-y-2">
              <Label for="message">{{ __('pricing.field_message') }}</Label>
              <textarea
                id="message"
                v-model="form.message"
                :placeholder="__('pricing.placeholder_message')"
                rows="3"
                class="flex w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2"
              />
              <p v-if="form.errors.message" class="text-sm text-red-500">{{ form.errors.message }}</p>
            </div>

            <DialogFooter>
              <Button type="button" variant="outline" @click="closeModal" class="cursor-pointer">
                {{ __('pricing.modal_cancel') }}
              </Button>
              <Button type="submit" :disabled="form.processing" class="bg-red-500 hover:bg-red-600 text-white cursor-pointer">
                <Send :size="16" class="mr-2" />
                {{ form.processing ? __('pricing.modal_sending') : __('pricing.modal_submit') }}
              </Button>
            </DialogFooter>
          </form>
      </DialogContent>
    </Dialog>
  </section>
</template>

<style scoped>
/* Animation du changement de prix */
.price-pop-enter-active, .price-pop-leave-active {
  transition: all 0.2s ease-out;
}
.price-pop-enter-from { opacity: 0; transform: translateY(10px); }
.price-pop-leave-to { opacity: 0; transform: translateY(-10px); }

/* Animation du reflet brillant sur le bouton */
@keyframes shimmer {
  100% { transform: translateX(300%); }
}
.animate-shimmer {
  animation: shimmer 0.8s ease-out;
}
</style>
