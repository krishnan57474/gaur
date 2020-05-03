interface UserConfigsInterface {
    context: HTMLElement;
    currentPage: number;
    filterBy: Array<string>;
    filterVal: Array<string>;
    listCount: number;
    orderBy: string;
    searchBy: Array<string>;
    searchVal: Array<string>;
    sortBy: number;
    url: string;
}

interface ConfigsInterface extends UserConfigsInterface {
    lock: boolean;
    totalItems: number;
    totalPage: number;
}
