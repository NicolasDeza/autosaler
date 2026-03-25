<template>
    <Head :title="`${ad.brand?.name} ${ad.model?.name}`" />

    <AppContent>
        <div class="mx-auto w-full space-y-8 py-4 pb-32 lg:py-8 lg:pb-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="col-span-1 space-y-8 lg:col-span-2">
                    <div
                        class="space-y-8 rounded-lg border bg-card p-4 shadow-sm sm:p-6"
                    >
                        <div
                            class="flex flex-col gap-6 p-1 sm:flex-row sm:items-start sm:justify-between sm:p-2"
                        >
                            <!-- Top: Navigation, Title and Mobile Star -->
                            <div
                                class="flex w-full items-start justify-between gap-4 sm:w-auto"
                            >
                                <div class="flex items-start gap-3 sm:gap-4">
                                    <button
                                        class="group flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-xl border border-border/50 bg-background/50 transition-all hover:border-primary/30 hover:bg-background hover:shadow-md"
                                        @click="
                                            router.visit(vehiclesIndex.url())
                                        "
                                        :title="__('vehicleAd.back_to_ads')"
                                    >
                                        <ChevronLeft
                                            class="h-5 w-5 text-muted-foreground transition-colors group-hover:text-primary"
                                        />
                                    </button>
                                    <div class="min-w-0 flex-1">
                                        <h1
                                            class="text-2xl font-bold sm:text-3xl"
                                        >
                                            {{ ad.brand?.name }}
                                            {{ ad.model?.name }}
                                        </h1>
                                        <h2
                                            class="mt-1 text-sm font-bold tracking-wider text-muted-foreground/80 uppercase"
                                        >
                                            {{ ad.vehicle_version_name }}
                                        </h2>
                                    </div>
                                </div>

                                <!-- Mobile Favorite -->
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="size-10 shrink-0 cursor-pointer rounded-md border border-border bg-background transition-all hover:border-primary hover:bg-primary/10 hover:text-primary sm:hidden"
                                    :class="{
                                        'border-primary/20 fill-primary text-primary':
                                            ad.is_favorited,
                                    }"
                                    @click="toggleFavorite"
                                >
                                    <Star
                                        class="size-5"
                                        :class="{
                                            'fill-primary': ad.is_favorited,
                                        }"
                                    />
                                </Button>
                            </div>

                            <!-- Right: Actions & Price -->
                            <div class="flex flex-col gap-4 sm:items-end">
                                <!-- Price Badge -->
                                <div
                                    class="inline-flex self-start bg-primary py-1 pr-6 pl-4 text-white shadow-sm [clip-path:polygon(0_0,90%_0,100%_100%,0_100%)] sm:self-auto sm:py-1.5 sm:pr-5 sm:pl-7 sm:[clip-path:polygon(10%_0,100%_0,100%_100%,0_100%)]"
                                >
                                    <div class="flex items-end gap-2">
                                        <span
                                            class="text-2xl font-black tracking-tight sm:text-3xl"
                                        >
                                            {{
                                                Number(ad.price).toLocaleString(
                                                    'fr-FR',
                                                )
                                            }}
                                            €
                                        </span>
                                        <span
                                            class="pb-0.5 text-[10px] font-semibold tracking-[0.18em] text-white/80 uppercase sm:pb-1 sm:text-[11px]"
                                        >
                                            {{ __('vehicleAd.vat_included') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Desktop Actions -->
                                <div class="hidden items-center gap-3 sm:flex">
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        class="size-10 shrink-0 cursor-pointer rounded-md border border-border bg-background transition-all hover:border-primary hover:bg-primary/10 hover:text-primary"
                                        :class="{
                                            'border-primary/20 fill-primary text-primary':
                                                ad.is_favorited,
                                        }"
                                        @click="toggleFavorite"
                                    >
                                        <Star
                                            class="size-5"
                                            :class="{
                                                'fill-primary': ad.is_favorited,
                                            }"
                                        />
                                    </Button>
                                    <Button
                                        variant="ghost"
                                        class="hidden h-10 cursor-pointer items-center gap-2 rounded-md border border-border bg-background px-3 transition-all hover:border-primary/30 lg:flex"
                                        :class="{
                                            'border-primary/20 bg-primary/5 text-primary':
                                                isSelected(ad.id),
                                        }"
                                        @click="toggleComparison"
                                    >
                                        <Checkbox
                                            :id="`compare-${ad.id}`"
                                            :model-value="isSelected(ad.id)"
                                            class="pointer-events-none size-4"
                                        />
                                        <span
                                            class="text-xs font-bold tracking-tight uppercase"
                                        >
                                            {{ __('vehicleAd.compare') }}
                                        </span>
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <VehicleGallery
                            :images="ad.gallery"
                            :brand-name="ad.brand?.name"
                            :model-name="ad.model?.name"
                        />

                        <div class="p-2 sm:p-0">
                            <div
                                class="grid grid-cols-2 gap-y-6 sm:grid-cols-3"
                            >
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-1.5">
                                        <Gauge
                                            class="h-3.5 w-3.5 text-primary"
                                        />
                                        <span
                                            class="text-xs font-bold text-muted-foreground uppercase"
                                            >{{ __('vehicleAd.mileage') }}</span
                                        >
                                    </div>
                                    <span class="font-semibold"
                                        >{{
                                            ad.mileage.toLocaleString()
                                        }}
                                        km</span
                                    >
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex items-center gap-1.5">
                                        <Calendar
                                            class="h-3.5 w-3.5 text-primary"
                                        />
                                        <span
                                            class="text-xs font-bold text-muted-foreground uppercase"
                                            >{{
                                                __(
                                                    'vehicleAd.first_registration',
                                                )
                                            }}</span
                                        >
                                    </div>
                                    <span class="font-semibold">{{
                                        new Date(
                                            ad.first_registration_date,
                                        ).toLocaleDateString()
                                    }}</span>
                                </div>
                                <div
                                    class="flex flex-col gap-1.5"
                                    v-if="ad.power_kw"
                                >
                                    <div class="flex items-center gap-1.5">
                                        <Zap class="h-3.5 w-3.5 text-primary" />
                                        <span
                                            class="text-xs font-bold text-muted-foreground uppercase"
                                            >{{ __('vehicleAd.power') }}</span
                                        >
                                    </div>
                                    <span class="font-semibold">
                                        {{ ad.power_kw }} kW ({{
                                            kwToHp(ad.power_kw)
                                        }}
                                        ch)
                                    </span>
                                </div>
                                <div
                                    class="flex flex-col gap-1.5"
                                    v-if="ad.fuel_type"
                                >
                                    <div class="flex items-center gap-1.5">
                                        <Fuel
                                            class="h-3.5 w-3.5 text-primary"
                                        />
                                        <span
                                            class="text-xs font-bold text-muted-foreground uppercase"
                                            >{{ __('vehicleAd.fuel') }}</span
                                        >
                                    </div>
                                    <span class="font-semibold">{{
                                        ad.fuel_type?.code
                                    }}</span>
                                </div>
                                <div
                                    class="flex flex-col gap-1.5"
                                    v-if="ad.transmission_type"
                                >
                                    <div class="flex items-center gap-1.5">
                                        <Cog class="h-3.5 w-3.5 text-primary" />
                                        <span
                                            class="text-xs font-bold text-muted-foreground uppercase"
                                            >{{
                                                __('vehicleAd.transmission')
                                            }}</span
                                        >
                                    </div>
                                    <span class="font-semibold">{{
                                        ad.transmission_type?.code
                                    }}</span>
                                </div>
                                <div
                                    class="flex flex-col gap-1.5"
                                    v-if="ad.body_type"
                                >
                                    <div class="flex items-center gap-1.5">
                                        <Truck
                                            class="h-3.5 w-3.5 text-primary"
                                        />
                                        <span
                                            class="text-xs font-bold text-muted-foreground uppercase"
                                            >{{ __('vehicleAd.body') }}</span
                                        >
                                    </div>
                                    <span class="font-semibold">{{
                                        ad.body_type?.code
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional details -->
                    <div class="rounded-lg border bg-card p-6 shadow-sm">
                        <h3 class="mb-6 text-lg font-bold">
                            {{ __('vehicleAd.technical_info') }}
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="text-sm">
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                    healthiest="true"
                                >
                                    <span class="text-muted-foreground">{{
                                        __('vehicleAd.displacement')
                                    }}</span>
                                    <span class="font-medium"
                                        >{{
                                            ad.engine_displacement ?? '-'
                                        }}
                                        cm³</span
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground">{{
                                        __('vehicleAd.cylinders')
                                    }}</span>
                                    <span class="font-medium">{{
                                        ad.cylinder_count ?? '-'
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground">{{
                                        __('vehicleAd.gears')
                                    }}</span>
                                    <span class="font-medium">{{
                                        ad.gear_count ?? '-'
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground">{{
                                        __('vehicleAd.weight')
                                    }}</span>
                                    <span class="font-medium"
                                        >{{ ad.weight_kg ?? '-' }} kg</span
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between py-2.5"
                                >
                                    <span class="text-muted-foreground">{{
                                        __('vehicleAd.doors_seats')
                                    }}</span>
                                    <span class="font-medium"
                                        >{{ ad.doors ?? '-' }} /
                                        {{ ad.seats ?? '-' }}</span
                                    >
                                </div>
                            </div>
                            <div class="text-sm">
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground">{{
                                        __('vehicleAd.euro_norm')
                                    }}</span>
                                    <span class="font-medium">{{
                                        ad.euro_norm?.code ?? '-'
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground">{{
                                        __('vehicleAd.co2_consumption')
                                    }}</span>
                                    <span class="font-medium"
                                        >{{ ad.co2_emission ?? '-' }} g/km /
                                        {{ ad.fuel_consumption_avg ?? '-' }}
                                        L</span
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground">{{
                                        __('vehicleAd.exterior_color')
                                    }}</span>
                                    <span class="font-medium"
                                        >{{ ad.exterior_color?.code ?? '-'
                                        }}<span
                                            v-if="ad.exterior_color_metalised"
                                        >
                                            ({{
                                                __('vehicleAd.metallic')
                                            }})</span
                                        ></span
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between border-b py-2.5"
                                >
                                    <span class="text-muted-foreground">{{
                                        __('vehicleAd.interior')
                                    }}</span>
                                    <span class="font-medium"
                                        >{{ ad.interior_color?.code ?? '-' }},
                                        {{
                                            ad.interior_type?.code ?? '-'
                                        }}</span
                                    >
                                </div>
                                <div
                                    class="flex items-center justify-between py-2.5"
                                >
                                    <span class="text-muted-foreground">{{
                                        __('vehicleAd.previous_owners')
                                    }}</span>
                                    <span class="font-medium">{{
                                        ad.previous_owner ?? '-'
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- State & History -->
                    <div class="rounded-lg border bg-card p-6 shadow-sm">
                        <h3 class="mb-6 text-lg font-bold">
                            {{ __('vehicleAd.state_history') }}
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                                :class="
                                    ad.is_damaged
                                        ? 'border-red-200 bg-red-50 text-red-600 dark:border-red-800 dark:bg-red-950/40 dark:text-red-400'
                                        : 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                                "
                            >
                                <component
                                    :is="
                                        ad.is_damaged
                                            ? AlertTriangle
                                            : CheckCircle
                                    "
                                    class="h-3.5 w-3.5"
                                />
                                {{ __('vehicleAd.damaged') }}:
                                {{ ad.is_damaged ? __('ui.yes') : __('ui.no') }}
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                                :class="
                                    ad.has_accident
                                        ? 'border-red-200 bg-red-50 text-red-600 dark:border-red-800 dark:bg-red-950/40 dark:text-red-400'
                                        : 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                                "
                            >
                                <component
                                    :is="
                                        ad.has_accident
                                            ? AlertTriangle
                                            : CheckCircle
                                    "
                                    class="h-3.5 w-3.5"
                                />
                                {{ __('vehicleAd.accident') }}:
                                {{
                                    ad.has_accident ? __('ui.yes') : __('ui.no')
                                }}
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                                :class="
                                    ad.complete_maintenance_book
                                        ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                                        : 'border-border bg-muted/50 text-muted-foreground/50'
                                "
                            >
                                <CheckCircle class="h-3.5 w-3.5" />
                                {{ __('vehicleAd.full_service_history') }}
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                                :class="
                                    ad.non_smoker
                                        ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                                        : 'border-border bg-muted/50 text-muted-foreground/50'
                                "
                            >
                                <CheckCircle class="h-3.5 w-3.5" />
                                {{ __('vehicleAd.non_smoker') }}
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold"
                                :class="
                                    ad.technical_inspection_status
                                        ? 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-950/40 dark:text-green-400'
                                        : 'border-border bg-muted/50 text-muted-foreground/50'
                                "
                            >
                                <CheckCircle class="h-3.5 w-3.5" />
                                {{ __('vehicleAd.technical_inspection') }}
                            </span>
                        </div>
                    </div>

                    <div
                        v-if="ad.features?.length"
                        class="rounded-lg border bg-card p-6 shadow-sm"
                    >
                        <h3 class="mb-6 text-lg font-bold">
                            {{ __('vehicleAd.equipment_options') }}
                        </h3>
                        <div class="space-y-6">
                            <div
                                v-for="category in groupedFeatures"
                                :key="category.id"
                                class="space-y-3"
                            >
                                <h4
                                    class="text-sm font-semibold tracking-wide text-muted-foreground uppercase"
                                >
                                    {{ formatOptionLabel(category.code) }}
                                </h4>
                                <div
                                    class="grid grid-cols-1 gap-3 sm:grid-cols-2"
                                >
                                    <div
                                        v-for="feature in category.features"
                                        :key="feature.id"
                                        class="flex items-start gap-2.5 text-sm text-foreground"
                                    >
                                        <CheckCircle
                                            class="mt-0.5 h-4 w-4 shrink-0 text-green-600"
                                        />
                                        <span>{{
                                            formatOptionLabel(
                                                feature.code ?? feature.key,
                                            )
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="ad.description"
                        class="rounded-lg border bg-card p-6 shadow-sm"
                    >
                        <h3 class="mb-4 text-lg font-bold">
                            {{ __('vehicleAd.description') }}
                        </h3>
                        <p class="whitespace-pre-wrap text-foreground">
                            {{ ad.description }}
                        </p>
                    </div>
                </div>

                <!-- Contact Sidebar (Desktop) -->
                <div
                    class="col-span-1 space-y-6 lg:sticky lg:top-24 lg:self-start"
                >
                    <Card
                        class="gap-0! overflow-hidden rounded-lg border border-border/50 bg-card/60 pt-0 shadow-2xl shadow-primary/5 backdrop-blur-sm transition-all hover:border-primary/20 hover:shadow-primary/10"
                    >
                        <!-- Desktop Header (Hidden on Mobile) -->
                        <div
                            class="dark hidden border-b border-border/50 bg-background px-6 py-8 text-foreground lg:block"
                        >
                            <div class="flex items-start justify-between">
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2">
                                        <Badge
                                            variant="outline"
                                            class="border-primary/20 bg-primary/5 text-[10px] font-black tracking-[0.2em] text-primary uppercase"
                                        >
                                            {{ __('vehicleAd.seller') }}
                                        </Badge>
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-green-500/10 px-2 py-0.5 text-[9px] font-bold text-green-600"
                                        >
                                            <CheckCircle class="size-2.5" />
                                            {{ __('ui.verified') || 'Vérifié' }}
                                        </span>
                                    </div>
                                    <div class="space-y-1">
                                        <h2
                                            class="text-xl leading-tight font-black tracking-tight text-foreground"
                                        >
                                            {{
                                                ad.user?.company?.name ??
                                                ad.user?.first_name +
                                                    ' ' +
                                                    ad.user?.last_name
                                            }}
                                        </h2>
                                        <p
                                            class="text-xs font-medium text-muted-foreground"
                                            v-if="ad.user?.created_at"
                                        >
                                            {{
                                                __('ui.member_since') ||
                                                'Membre depuis'
                                            }}
                                            {{
                                                new Date(
                                                    ad.user.created_at,
                                                ).getFullYear()
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <a
                                    v-if="ad.user?.company?.phone"
                                    :href="`https://wa.me/${ad.user.company.phone?.replace(/\D/g, '')}?text=${encodeURIComponent(__('vehicleAd.whatsapp_message'))}`"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="relative flex h-14 w-14 items-center justify-center rounded-2xl bg-[#25D366] text-white shadow-lg shadow-[#25D366]/20 transition-all hover:scale-105 hover:shadow-[#25D366]/40 active:scale-95"
                                    title="WhatsApp"
                                >
                                    <svg
                                        viewBox="0 0 24 24"
                                        class="h-7 w-7 fill-current"
                                    >
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
                                        />
                                    </svg>
                                    <div
                                        class="absolute -top-1 -right-1 block h-3 w-3 rounded-full border-2 border-card bg-green-500"
                                    ></div>
                                </a>
                            </div>
                        </div>

                        <!-- Card Body (Desktop Location / Sidebar Actions) -->
                        <div class="hidden p-6 lg:block">
                            <div v-if="ad.user?.company" class="mb-8 space-y-4">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/10 text-primary"
                                    >
                                        <MapPin class="h-4 w-4" />
                                    </div>
                                    <p
                                        class="text-[11px] font-black tracking-widest text-muted-foreground/60 uppercase"
                                    >
                                        {{ __('ui.location') }}
                                    </p>
                                </div>
                                <div
                                    class="rounded-xl border border-border/50 bg-muted/30 p-4 text-sm leading-relaxed text-foreground/80"
                                >
                                    <p class="font-bold text-foreground">
                                        {{ ad.user.company.address }}
                                    </p>
                                    <p>
                                        {{ ad.user.company.city?.zip_code }}
                                        {{ ad.user.company.city?.code }}
                                    </p>
                                    <p
                                        class="mt-2 flex items-center gap-1.5 text-[11px] font-bold text-primary uppercase"
                                    >
                                        <Globe class="size-3" />
                                        {{ ad.user.company.country?.code }}
                                    </p>
                                </div>
                            </div>

                            <div class="mb-6 grid grid-cols-2 gap-3">
                                <a
                                    v-if="ad.user?.company?.phone"
                                    :href="`tel:${ad.user.company.phone}`"
                                    class="group flex flex-col items-center gap-2 rounded-xl border border-border bg-muted/50 py-3.5 transition-all hover:border-primary/30 hover:bg-primary/5"
                                >
                                    <Phone
                                        class="h-4 w-4 text-muted-foreground transition-colors group-hover:text-primary"
                                    />
                                    <span
                                        class="text-[10px] font-bold tracking-tight text-muted-foreground uppercase"
                                        >{{ __('ui.call') }}</span
                                    >
                                </a>

                                <Button
                                    type="button"
                                    class="group relative flex h-16 flex-1 items-center justify-center overflow-hidden rounded-xl bg-primary px-6 font-black tracking-tight text-white uppercase shadow-lg shadow-primary/20 transition-all hover:scale-[1.02] hover:shadow-primary/40 active:scale-[0.98]"
                                    @click="openContactModal"
                                >
                                    <div
                                        class="absolute inset-0 bg-linear-to-r from-transparent via-white/10 to-transparent transition-transform duration-1000 group-hover:translate-x-full"
                                    ></div>
                                    <div
                                        class="flex flex-col items-center gap-2"
                                    >
                                        <Mail
                                            class="h-4 w-4 transition-transform group-hover:-rotate-12"
                                        />
                                        <span
                                            class="text-xs font-bold whitespace-break-spaces"
                                            >{{
                                                __('vehicleAd.contact_seller')
                                            }}</span
                                        >
                                    </div>
                                </Button>
                            </div>
                        </div>
                    </Card>

                    <Card
                        v-if="canEdit"
                        class="hidden border-2 border-dashed bg-muted/10 p-4 shadow-none lg:block"
                    >
                        <h3
                            class="mb-3 text-[10px] font-black tracking-[0.2em] text-muted-foreground/70 uppercase"
                        >
                            {{ __('vehicleAd.ad_management') }}
                        </h3>
                        <Button
                            variant="outline"
                            class="w-full cursor-pointer justify-start gap-2 rounded border-border/50 bg-background"
                            @click="router.visit(vehicleEdit.url(ad.id))"
                        >
                            <Edit class="h-3.5 w-3.5 text-primary" />
                            <span class="text-xs font-bold">{{
                                __('vehicleAd.edit_ad')
                            }}</span>
                        </Button>
                    </Card>
                </div>
            </div>
        </div>

        <template #sticky-bottom>
            <!-- Mobile Sticky Contact Bar -->
            <div class="px-2 py-2 lg:hidden">
                <div class="flex items-center justify-between">
                    <div class="flex min-w-0 flex-col gap-0.5">
                        <h2
                            class="flex items-center gap-1 text-sm font-bold tracking-tight text-foreground"
                        >
                            <span class="truncate">
                                {{
                                    ad.user?.company?.name ??
                                    ad.user?.first_name +
                                        ' ' +
                                        ad.user?.last_name
                                }}
                            </span>
                            <CheckCircle
                                class="size-3.5 shrink-0 text-green-500"
                            />
                        </h2>
                        <div
                            v-if="ad.user?.company?.city"
                            class="flex items-center gap-1 text-[10px] font-bold text-muted-foreground uppercase"
                        >
                            <MapPin class="size-3 shrink-0" />
                            <span class="truncate sm:hidden">{{
                                ad.user.company.city.code
                            }}</span>
                            <span class="hidden truncate sm:inline">
                                {{ ad.user.company.address }},
                                {{ ad.user.company.city.zip_code }}
                                {{ ad.user.company.city.code }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a
                            v-if="ad.user?.company?.phone"
                            :href="`tel:${ad.user.company.phone}`"
                            class="flex h-12 items-center justify-center rounded-xl border border-border bg-primary/10 px-2 transition-all hover:bg-primary/25 active:scale-95"
                            :title="__('ui.call')"
                        >
                            <Phone class="size-5 text-primary" />
                        </a>

                        <a
                            v-if="ad.user?.company?.phone"
                            :href="`https://wa.me/${ad.user.company.phone?.replace(/\D/g, '')}?text=${encodeURIComponent(__('vehicleAd.whatsapp_message'))}`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex h-12 items-center justify-center rounded-xl bg-[#25D366]/10 px-2 text-[#25D366] ring-1 ring-[#25D366]/20 transition-all hover:bg-[#25D366]/20 active:scale-95"
                            title="WhatsApp"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                class="size-5 fill-current"
                            >
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"
                                />
                            </svg>
                        </a>

                        <Button
                            type="button"
                            size="sm"
                            class="h-12 rounded-xl bg-primary px-4 font-black tracking-tight text-white uppercase shadow-lg shadow-primary/20 active:scale-95"
                            @click="openContactModal"
                        >
                            <Mail class="size-4" />
                            <span class="max-sm:hidden">{{
                                __('vehicleAd.contact') || 'Contact'
                            }}</span>
                        </Button>
                    </div>
                </div>
            </div>
        </template>
    </AppContent>

    <DealerContactModal
        v-model:open="showContactModal"
        :vehicle-ad-id="ad.id"
    />

    <LoginRequiredModal
        v-model:open="showLoginModal"
        :title="__('vehicleAd.favorites.add')"
        :description="__('vehicleAd.favorites.login_required')"
    />

    <!-- Edit button (Seller only, Teleported to the left next to '+' button) -->
    <Teleport v-if="canEdit" defer to="#sticky-bottom-mobile-left-portal">
        <Link
            :href="vehicleEdit.url(ad.id)"
            class="bottom-bar-tool-btn ml-1"
            :title="__('vehicleAd.edit_ad')"
        >
            <Edit />
        </Link>
    </Teleport>
</template>

<script setup lang="ts">
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    CheckCircle,
    AlertTriangle,
    Edit,
    Phone,
    Gauge,
    Calendar,
    Zap,
    Fuel,
    Cog,
    Truck,
    ChevronLeft,
    Star,
    MapPin,
    Globe,
    Mail,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import AppContent from '@/components/AppContent.vue';
import LoginRequiredModal from '@/components/Auth/LoginRequiredModal.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import DealerContactModal from '@/components/VehicleAds/DealerContactModal.vue';
import VehicleGallery from '@/components/VehicleAds/VehicleGallery.vue';
import { useComparison } from '@/composables/useComparison';
import { useTranslation } from '@/composables/useTranslation';
import { kwToHp } from '@/lib/utils';
import {
    edit as vehicleEdit,
    index as vehiclesIndex,
    favorite as vehicleFavorite,
} from '@/routes/vehicles';

const props = defineProps<{
    ad: any;
    canEdit: boolean;
}>();

const { __ } = useTranslation();

type FeatureCategory = {
    id?: number | string;
    code?: string;
    key?: string;
    sort_order?: number | null;
};

type FeatureOption = {
    id: number | string;
    code?: string;
    key?: string;
    sort_order?: number | null;
    category?: FeatureCategory | null;
};

type GroupedFeature = {
    id: string;
    code: string;
    sortOrder: number;
    features: FeatureOption[];
};

const showLoginModal = ref(false);
const showContactModal = ref(false);
const page = usePage();
const { addVehicle, removeVehicle, isSelected } = useComparison();

const toggleComparison = () => {
    if (isSelected(props.ad.id)) {
        removeVehicle(props.ad.id);
    } else {
        addVehicle({
            id: props.ad.id,
            brand: props.ad.brand?.name,
            model: props.ad.model?.name,
            vehicle_version_name:
                props.ad.vehicle_version_name || props.ad.vehicle_version?.name,
            price: Number(props.ad.price),
        });
    }
};

const formatOptionLabel = (value?: string): string => {
    if (!value) {
        return '';
    }

    return value.replace(/[_-]+/g, ' ').trim();
};

const groupedFeatures = computed<GroupedFeature[]>(() => {
    const groups = new Map<string, GroupedFeature>();
    const features: FeatureOption[] = Array.isArray(props.ad?.features)
        ? props.ad.features
        : [];

    for (const feature of features) {
        const categoryCode =
            feature.category?.code ?? feature.category?.key ?? 'autres';
        const categoryId = String(feature.category?.id ?? categoryCode);
        const existingGroup = groups.get(categoryId);

        if (existingGroup) {
            existingGroup.features.push(feature);
            continue;
        }

        groups.set(categoryId, {
            id: categoryId,
            code: categoryCode,
            sortOrder: feature.category?.sort_order ?? Number.MAX_SAFE_INTEGER,
            features: [feature],
        });
    }

    return [...groups.values()]
        .sort((a, b) => {
            if (a.sortOrder !== b.sortOrder) {
                return a.sortOrder - b.sortOrder;
            }

            return a.code.localeCompare(b.code, 'fr');
        })
        .map((group) => ({
            ...group,
            features: [...group.features].sort((a, b) => {
                const left = a.code ?? a.key ?? '';
                const right = b.code ?? b.key ?? '';

                return left.localeCompare(right, 'fr');
            }),
        }));
});

const openContactModal = (): void => {
    showContactModal.value = true;
};

const toggleFavorite = () => {
    if (!page.props.auth?.user) {
        showLoginModal.value = true;
        return;
    }
    router.post(
        vehicleFavorite.url({ vehicleAd: props.ad.id }),
        {},
        { preserveScroll: true },
    );
};
</script>
