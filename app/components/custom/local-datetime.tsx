"use client"

interface LocalDateTimeProps {
    isoDate: string
    options?: Intl.DateTimeFormatOptions
}

const DEFAULT_OPTIONS: Intl.DateTimeFormatOptions = {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: 'numeric',
    minute: 'numeric',
}

const LocalDateTime = ({ isoDate, options = DEFAULT_OPTIONS }: LocalDateTimeProps) => {
    const date = new Date(isoDate)
    return <>{date.toLocaleString("fr-FR", options)}</>
}

export { LocalDateTime }
