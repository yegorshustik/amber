export interface WxLengthAwarePaginationLinks {
    url: string;
    page: number | null;
    label: string;
    active: boolean;
}

export interface WxLengthAwarePagination<T> {
    data : T[];
    links : {
        first : string;
        last : string;
        prev : string | null;
        next : string | null;
    };
    meta : {
        current_page : number;
        from : number;
        links : WxLengthAwarePaginationLinks[];
        last_page : number;
        path : string;
        per_page : number;
        to : number;
        total : number;
    }

}
