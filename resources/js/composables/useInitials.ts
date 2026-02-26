export type UseInitialsReturn = {
    getInitials: (firstName?: string, lastName?: string) => string;
};

export function getInitials(firstName?: string, lastName?: string): string {
    if (!firstName || !lastName) return '';

    return `${firstName.charAt(0).toUpperCase()}${lastName.charAt(0).toUpperCase()}`;
}

export function useInitials(): UseInitialsReturn {
    return { getInitials };
}
