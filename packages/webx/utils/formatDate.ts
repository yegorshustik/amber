export function formatDate(date, locale: string = 'uk-UA') {
    const formatter = new Intl.DateTimeFormat(locale, {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
    });

    return formatter.format(new Date(date));
}
