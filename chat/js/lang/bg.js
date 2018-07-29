/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @author Borislav Manolov
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */
// Ajax Chat language Object:
var ajaxChatLang = {
    login: '%s влезе в чата.',
    logout: '%s излезе от чата.',
    logoutTimeout: '%s излезе автоматично от чата (Изтичане на времето).',
    logoutIP: '%s излезе автоматично от чата (Грешен айпи адрес).',
    logoutKicked: '%s излезе автоматично от чата (Изритване).',
    channelEnter: '%s влезе в канала.',
    channelLeave: '%s напусна канала.',
    privmsg: '(прошепва)',
    privmsgto: '(прошепва на %s)',
    invite: '%s ви кани да се присъедините към %s.',
    inviteto: 'Поканата ви към %s да се присъедини към канала %s беше изпратена.',
    uninvite: '%s отмени поканата ви за канала %s.',
    uninviteto: 'Отмяната на поканата ви към %s за канала %s беше изпратена.',
    queryOpen: 'Отворен е личен канал за %s.',
    queryClose: 'Затворен е личен канал за %s.',
    ignoreAdded: '%s беше добавен към списъка с пренебрегнатите.',
    ignoreRemoved: '%s беше изваден от списъка с пренебрегнатите.',
    ignoreList: 'Пренебрегнати потребители:',
    ignoreListEmpty: 'Няма пренебрегнати потребители.',
    who: 'Потребители на линия:',
    whoChannel: 'Потребители на линия в канала %s:',
    whoEmpty: 'В дадения канал няма потребители на линия.',
    list: 'Налични канали:',
    bans: 'Изгонени потребители:',
    bansEmpty: 'Няма изгонени потребители.',
    unban: 'Изгонването на потребителя %s е отменено.',
    whois: 'Потребител %s — айпи адрес:',
    whereis: 'Потребителят %s е в канала %s.',
    roll: '%s хвърли %s и получи %s.',
    nick: '%s вече се казва %s.',
    toggleUserMenu: 'Показване/скриване на потребителското меню за %s',
    userMenuLogout: 'Изход',
    userMenuWho: 'Потребители на линия',
    userMenuList: 'Налични канали',
    userMenuAction: 'Описване на действие',
    userMenuRoll: 'Хвърляне на зар',
    userMenuNick: 'Смяна на името',
    userMenuEnterPrivateRoom: 'Влизане в личната стая',
    userMenuSendPrivateMessage: 'Изпращане на лично съобщение',
    userMenuDescribe: 'Изпращане на лично действие',
    userMenuOpenPrivateChannel: 'Отваряне на личен канал',
    userMenuClosePrivateChannel: 'Затваряне на личен канал',
    userMenuInvite: 'Покана',
    userMenuUninvite: 'Отмяна на покана',
    userMenuIgnore: 'Пренебрегване/Приемане',
    userMenuIgnoreList: 'Пренебрегнати потребители',
    userMenuWhereis: 'Преглед на канал',
    userMenuKick: 'Изритване/Изгонване',
    userMenuBans: 'Изгонени потребители',
    userMenuWhois: 'Преглед на айпи адреса',
    unbanUser: 'Отмяна на изгонването на %s',
    joinChannel: 'Присъединяване към канала %s',
    cite: '%s каза:',
    urlDialog: 'Моля, въведете адреса (URL) на страницата:',
    deleteMessage: 'Изтриване на съобщението',
    deleteMessageConfirm: 'Наистина ли желаете да изтриете съобщението?',
    errorCookiesRequired: 'За чата се изискват бисквитки (cookies).',
    errorUserNameNotFound: 'Грешка: Не е намерен потребител %s.',
    errorMissingText: 'Грешка: Липсва текст на съобщението.',
    errorMissingUserName: 'Грешка: Липсва потребителско име.',
    errorInvalidUserName: 'Грешка: Невалидно потребителско име.',
    errorUserNameInUse: 'Грешка: Това потребителско име вече се използва.',
    errorMissingChannelName: 'Грешка: Липсва име на канал.',
    errorInvalidChannelName: 'Грешка: Невалидно име на канал: %s',
    errorPrivateMessageNotAllowed: 'Грешка: Личните съобщения не са позволени.',
    errorInviteNotAllowed: 'Грешка: Не ви е позволено да каните потребители в този канал.',
    errorUninviteNotAllowed: 'Грешка: Не ви е позволено да отменяте покани в този канал.',
    errorNoOpenQuery: 'Грешка: Не е отворен личен канал.',
    errorKickNotAllowed: 'Грешка: Не ви е позволено да изритвате %s.',
    errorCommandNotAllowed: 'Грешка: Командата не е позволена: %s',
    errorUnknownCommand: 'Грешка: Непозната команда: %s',
    errorMaxMessageRate: 'Грешка: Превишихте допустимия брой съобщения в минута.',
    errorConnectionTimeout: 'Грешка: Изтичане на времето за връзка. Моля, опитайте отново!',
    errorConnectionStatus: 'Грешка: Състояние на връзката: %s',
    errorSoundIO: 'Грешка: Неуспешно зареждане на звуковия файл (Входно-изходна грешка при Флаш).',
    errorSocketIO: 'Грешка: Неуспешна връзка към сокетния сървър (Входно-изходна грешка при Флаш).',
    errorSocketSecurity: 'Грешка: Неуспешна връзка към сокетния сървър (Грешка в сигурността при Флаш).',
    errorDOMSyntax: 'Грешка: Неправилен синтаксис при DOM (DOM ID: %s).',
    errorNotEnoughRep: 'Error: Not enough reputaion points.',
    errorNotEnoughKarma: 'Error: Not enough karma(seedbonus) points.'
};
