<template>
    <Card
        class="overflow-hidden border-border/40 bg-background/50 shadow-xl backdrop-blur-xl"
    >
        <CardHeader class="border-b border-border/40 bg-muted/20 pb-8">
            <div class="flex flex-col gap-8">
                <div class="space-y-2">
                    <CardTitle
                        class="font-heading text-3xl font-bold tracking-tight"
                    >
                        {{ __('admin.tab_users') }}
                    </CardTitle>
                    <CardDescription
                        class="font-body max-w-2xl text-sm leading-relaxed text-pretty"
                    >
                        {{ __('admin.users_description') }}
                    </CardDescription>
                </div>

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                    <div class="space-y-1.5">
                        <Label
                            class="font-heading ml-1 text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                            >{{ __('admin.role') }}</Label
                        >
                        <Select
                            v-model="roleFilter"
                            @update:model-value="updateFilters"
                        >
                            <SelectTrigger
                                class="font-heading h-11 w-full border-border/40 bg-background/50 text-xs font-bold tracking-widest uppercase sm:w-48"
                            >
                                <SelectValue
                                    :placeholder="__('admin.all_roles')"
                                />
                            </SelectTrigger>
                            <SelectContent class="font-body">
                                <SelectItem value="all">{{
                                    __('admin.all_roles')
                                }}</SelectItem>
                                <SelectItem
                                    v-for="role in roles"
                                    :key="role.id"
                                    :value="role.name"
                                >
                                    {{ __('admin.roles.' + role.name) }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div class="flex-1 space-y-1.5 sm:w-80">
                        <Label
                            class="font-heading ml-1 text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                            >{{ __('admin.search') }}</Label
                        >
                        <div class="group relative">
                            <Search
                                class="absolute top-1/2 left-3.5 h-4 w-4 -translate-y-1/2 text-muted-foreground transition-colors group-focus-within:text-primary"
                            />
                            <Input
                                v-model="search"
                                :placeholder="
                                    __('admin.search_users_placeholder')
                                "
                                autocomplete="off"
                                class="font-body h-11 border-border/40 bg-background/50 pl-10 text-sm focus-visible:ring-primary/20"
                                @input="debouncedSearch"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </CardHeader>

        <CardContent class="p-0">
            <div class="overflow-x-auto">
                <Table>
                    <TableHeader>
                        <TableRow
                            class="border-b border-border/40 bg-muted/30 transition-none"
                        >
                            <TableHead
                                class="font-heading px-8 py-5 text-[10px] font-bold tracking-[0.2em] text-muted-foreground uppercase"
                            >
                                {{ __('admin.user_info') }}
                            </TableHead>
                            <TableHead
                                class="font-heading py-5 text-[10px] font-bold tracking-[0.2em] text-muted-foreground uppercase"
                            >
                                {{ __('admin.role') }}
                            </TableHead>
                            <TableHead
                                class="font-heading py-5 text-[10px] font-bold tracking-[0.2em] text-muted-foreground uppercase"
                            >
                                {{ __('admin.company_subscription') }}
                            </TableHead>
                            <TableHead
                                class="font-heading py-5 pr-8 text-right text-[10px] font-bold tracking-[0.2em] text-muted-foreground uppercase"
                            >
                                {{ __('admin.actions') }}
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow
                            v-for="user in users.data"
                            :key="user.id"
                            class="group border-b border-border/40 transition-all duration-300 hover:bg-primary/2"
                        >
                            <TableCell class="px-8 py-5">
                                <div class="flex items-center gap-5">
                                    <div class="relative">
                                        <div
                                            class="font-heading flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-linear-to-br from-primary/15 to-primary/5 text-sm font-bold text-primary shadow-xs transition-all duration-500 group-hover:rotate-3 group-hover:rounded-xl"
                                        >
                                            {{ user.first_name?.[0]
                                            }}{{ user.last_name?.[0] }}
                                        </div>
                                        <div
                                            class="absolute -right-1 -bottom-1 h-3.5 w-3.5 rounded-full border-2 border-background shadow-xs"
                                            :class="getStatusColor(user.status)"
                                        ></div>
                                    </div>
                                    <div class="flex min-w-0 flex-col">
                                        <span
                                            class="font-heading truncate text-sm font-bold tracking-tight text-foreground/90"
                                        >
                                            {{ user.first_name }}
                                            {{ user.last_name }}
                                        </span>
                                        <span
                                            class="font-body truncate text-xs font-medium text-muted-foreground"
                                        >
                                            {{ user.email }}
                                        </span>
                                    </div>
                                </div>
                            </TableCell>

                            <TableCell class="py-5">
                                <div class="flex flex-wrap gap-1.5">
                                    <Badge
                                        v-for="role in user.roles"
                                        :key="role.id"
                                        variant="outline"
                                        class="font-heading rounded-lg border-primary/20 bg-primary/5 px-2.5 py-0.5 text-[9px] font-bold tracking-wider text-primary uppercase"
                                    >
                                        {{ __('admin.roles.' + role.name) }}
                                    </Badge>
                                </div>
                            </TableCell>

                            <TableCell class="py-5">
                                <div
                                    v-if="user.company"
                                    class="flex flex-col gap-2"
                                >
                                    <div
                                        class="font-heading flex items-center gap-2 text-xs font-bold text-foreground/80"
                                    >
                                        <Building2
                                            class="h-3.5 w-3.5 text-primary/60"
                                        />
                                        <span class="truncate">{{
                                            user.company.name
                                        }}</span>
                                    </div>
                                    <div
                                        v-if="activeSubscription(user)"
                                        class="flex items-center gap-2"
                                    >
                                        <Badge
                                            variant="outline"
                                            class="font-heading border-green-500/20 bg-green-500/5 px-1.5 py-0 text-[8px] font-extrabold tracking-widest text-green-600 uppercase"
                                        >
                                            {{
                                                activeSubscription(user).plan
                                                    ?.key
                                            }}
                                        </Badge>
                                        <span
                                            v-if="
                                                activeSubscription(user).ends_at
                                            "
                                            class="font-body text-[9px] font-medium text-muted-foreground/60 italic"
                                        >
                                            exp:
                                            {{
                                                new Date(
                                                    activeSubscription(user)
                                                        .ends_at,
                                                ).toLocaleDateString()
                                            }}
                                        </span>
                                    </div>
                                    <div
                                        v-else
                                        class="font-heading text-[9px] font-bold tracking-widest text-amber-500/80 uppercase"
                                    >
                                        {{ __('admin.no_active_subscription') }}
                                    </div>
                                </div>
                                <div v-else class="w-10 text-center">
                                    <span
                                        class="text-xs font-bold text-muted-foreground/20"
                                        >--</span
                                    >
                                </div>
                            </TableCell>

                            <TableCell class="py-5 pr-8 text-right">
                                <Button
                                    variant="outline"
                                    size="sm"
                                    class="font-heading h-9 w-9 border-border/40 bg-background/50 p-0 text-foreground/60 transition-all duration-300 hover:border-primary/50 hover:bg-primary/5 hover:text-primary"
                                    @click="openEditModal(user)"
                                >
                                    <Edit2 class="h-4 w-4" />
                                </Button>
                            </TableCell>
                        </TableRow>

                        <TableRow v-if="users.data.length === 0">
                            <TableCell colspan="4" class="py-32 text-center">
                                <div
                                    class="flex flex-col items-center gap-4 opacity-30"
                                >
                                    <div
                                        class="flex h-16 w-16 items-center justify-center rounded-3xl bg-muted shadow-inner"
                                    >
                                        <Users class="h-8 w-8" />
                                    </div>
                                    <p
                                        class="font-heading text-sm font-bold tracking-widest uppercase"
                                    >
                                        {{ __('admin.no_users_found') }}
                                    </p>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <AppPagination
                v-if="users"
                :pagination="users"
                :per-page="users.per_page"
                :resource-label="__('admin.users_label')"
                @update:page="handlePageChange"
                @update:per-page="handlePerPageChange"
                class="border-t border-border/40 bg-muted/20 px-8 py-5"
            />
        </CardContent>
    </Card>

    <!-- User Edit Modal -->
    <Dialog v-model:open="showModal">
        <DialogContent class="font-body sm:max-w-[450px]">
            <DialogHeader>
                <DialogTitle class="font-heading text-2xl tracking-tight">{{
                    __('admin.edit_user_title')
                }}</DialogTitle>
                <DialogDescription class="font-body leading-relaxed">
                    {{
                        __('admin.edit_user_desc', {
                            name:
                                selectedUser?.first_name +
                                ' ' +
                                selectedUser?.last_name,
                        })
                    }}
                </DialogDescription>
            </DialogHeader>

            <div class="my-4 space-y-8 border-y border-border/40 py-4">
                <!-- Status Management -->
                <div class="space-y-4">
                    <Label
                        class="font-heading text-[10px] font-bold tracking-[0.2em] text-muted-foreground uppercase"
                    >
                        {{ __('admin.account_status') }}
                    </Label>
                    <Select v-model="selectedUserStatus">
                        <SelectTrigger
                            class="font-heading h-12 border-border/40 bg-muted/20 text-xs font-bold tracking-widest uppercase"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-2 w-2 rounded-full"
                                    :class="getStatusColor(selectedUserStatus)"
                                ></div>
                                <SelectValue
                                    :placeholder="__('admin.select_status')"
                                />
                            </div>
                        </SelectTrigger>
                        <SelectContent class="font-body">
                            <SelectItem value="active" class="text-sm">{{
                                __('admin.status_user_active')
                            }}</SelectItem>
                            <SelectItem value="inactive" class="text-sm">{{
                                __('admin.status_user_inactive')
                            }}</SelectItem>
                            <SelectItem value="suspended" class="text-sm">{{
                                __('admin.status_user_suspended')
                            }}</SelectItem>
                            <SelectItem value="banned" class="text-sm">{{
                                __('admin.status_user_banned')
                            }}</SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <!-- Subscription Management -->
                <div v-if="isDealer(selectedUser)" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <Label
                            class="font-heading text-[10px] font-bold tracking-[0.2em] text-muted-foreground uppercase"
                        >
                            {{ __('admin.company_subscription') }}
                        </Label>
                        <div
                            v-if="activeSubscription(selectedUser)"
                            class="font-heading flex items-center gap-1.5 text-[10px] font-extrabold text-green-500 uppercase"
                        >
                            <div
                                class="h-1.5 w-1.5 animate-pulse rounded-full bg-green-500"
                            ></div>
                            {{ __('admin.active') }}
                        </div>
                    </div>

                    <div class="space-y-3">
                        <Select v-model="selectedPlanId">
                            <SelectTrigger
                                class="font-body h-12 border-border/40 bg-background/50 text-sm font-medium"
                            >
                                <SelectValue
                                    :placeholder="__('admin.select_plan')"
                                />
                            </SelectTrigger>
                            <SelectContent class="font-body">
                                <SelectItem
                                    v-for="plan in subscriptionPlans"
                                    :key="plan.id"
                                    :value="plan.id.toString()"
                                    class="text-sm"
                                >
                                    {{ plan.key }} —
                                    <span class="font-bold text-primary"
                                        >{{ plan.price }}€</span
                                    >
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <div v-if="activeSubscription(selectedUser)" class="px-1">
                        <Button
                            variant="link"
                            class="font-heading h-auto p-0 text-[10px] font-bold tracking-widest text-destructive/70 uppercase transition-colors hover:text-destructive"
                            @click="cancelSubscription"
                        >
                            <Trash2 class="mr-1.5 h-3 w-3" />
                            {{ __('admin.cancel_subscription_action') }}
                        </Button>
                    </div>
                </div>
            </div>

            <DialogFooter class="gap-3 sm:gap-1.5">
                <Button
                    variant="ghost"
                    class="font-heading h-11 px-6 text-[10px] font-bold tracking-widest text-muted-foreground uppercase"
                    @click="showModal = false"
                >
                    {{ __('admin.close') }}
                </Button>
                <Button
                    :disabled="processing"
                    class="font-heading h-11 flex-1 px-8 text-[10px] font-bold tracking-widest uppercase shadow-lg shadow-primary/20"
                    @click="saveUserSettings"
                >
                    <Spinner v-if="processing" class="mr-2 h-4 w-4" />
                    {{ __('admin.save_changes') }}
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { Search, Building2, Edit2, Trash2, Users } from 'lucide-vue-next';
import { ref } from 'vue';
import {
    index as adminDashboardIndex,
    updateSubscription as updateUserSubscription,
    cancelSubscription as cancelUserSubscription,
    updateStatus as updateUserStatus,
} from '@/actions/App/Http/Controllers/Admin/AdminDashboardController';
import AppPagination from '@/components/AppPagination.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { useTranslation } from '@/composables/useTranslation';

const props = defineProps<{
    users: any;
    filters: any;
    roles: any[];
    subscriptionPlans: any[];
}>();

const { __ } = useTranslation();

const search = ref(props.filters.search || '');
const roleFilter = ref(props.filters.role || 'all');
const showModal = ref(false);
const selectedUser = ref<any>(null);
const selectedUserStatus = ref<string>('active');
const selectedPlanId = ref<string>('');
const processing = ref(false);

const getStatusColor = (status: string) => {
    switch (status) {
        case 'active':
            return 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]';
        case 'suspended':
            return 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.4)]';
        case 'banned':
            return 'bg-destructive shadow-[0_0_8px_rgba(220,38,38,0.4)]';
        default:
            return 'bg-slate-400';
    }
};

const updateFilters = () => {
    router.get(
        adminDashboardIndex.url({
            query: {
                tab: 'users',
                search: search.value,
                role: roleFilter.value === 'all' ? undefined : roleFilter.value,
                per_page: props.users?.per_page,
            },
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};

const debouncedSearch = useDebounceFn(updateFilters, 500);

const activeSubscription = (user: any) => {
    return user?.subscriptions?.find((s: any) => s.status === 'active');
};

const isDealer = (user: any) => {
    return user?.roles.some((r: any) => r.name === 'dealer');
};

const openEditModal = (user: any) => {
    selectedUser.value = user;
    selectedUserStatus.value = user.status;
    const activeSub = activeSubscription(user);
    selectedPlanId.value = activeSub
        ? activeSub.subscription_plan_id.toString()
        : '';
    showModal.value = true;
};

const saveUserSettings = async () => {
    if (!selectedUser.value) return;

    processing.value = true;

    try {
        // Update Status if changed
        if (selectedUserStatus.value !== selectedUser.value.status) {
            await new Promise((resolve, reject) => {
                router.patch(
                    updateUserStatus.url(selectedUser.value.id),
                    { status: selectedUserStatus.value },
                    {
                        onSuccess: resolve,
                        onError: reject,
                        preserveScroll: true,
                    },
                );
            });
        }

        // Update Subscription if changed
        const activeSub = activeSubscription(selectedUser.value);
        if (
            isDealer(selectedUser.value) &&
            selectedPlanId.value &&
            (!activeSub ||
                selectedPlanId.value !==
                    activeSub.subscription_plan_id.toString())
        ) {
            await new Promise((resolve, reject) => {
                router.patch(
                    updateUserSubscription.url(selectedUser.value.id),
                    { subscription_plan_id: selectedPlanId.value },
                    {
                        onSuccess: resolve,
                        onError: reject,
                        preserveScroll: true,
                    },
                );
            });
        }

        showModal.value = false;
    } catch (error) {
        console.error('Error saving user settings:', error);
    } finally {
        processing.value = false;
    }
};

const cancelSubscription = () => {
    if (!selectedUser.value) return;

    if (confirm(__('admin.confirm_cancel_subscription'))) {
        processing.value = true;
        router.delete(cancelUserSubscription.url(selectedUser.value.id), {
            onSuccess: () => {
                showModal.value = false;
                processing.value = false;
            },
            onError: () => {
                processing.value = false;
            },
        });
    }
};

const handlePageChange = (page: number) => {
    router.get(
        adminDashboardIndex.url({
            query: {
                ...props.filters,
                tab: 'users',
                page: page,
            },
        }),
        {},
        { preserveState: true },
    );
};

const handlePerPageChange = (pp: string) => {
    router.get(
        adminDashboardIndex.url({
            query: {
                ...props.filters,
                tab: 'users',
                per_page: pp,
                page: 1,
            },
        }),
        {},
        { preserveState: true },
    );
};
</script>
