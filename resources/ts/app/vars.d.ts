interface UserConfigsInterface {
    context: JQuery<HTMLElement>;
    currentPage: number;
    filterBy: Array<string>;
    filterVal: Array<string>;
    listCount: number;
    orderBy: string;
    searchBy: Array<string>;
    searchVal: Array<string>;
    sortBy: number;
    [k: string]: JQuery<HTMLElement> | Array<string> | number | string | boolean;
}

interface ConfigsInterface extends UserConfigsInterface {
    lock: boolean;
    totalItems: number;
    totalPage: number;
}
