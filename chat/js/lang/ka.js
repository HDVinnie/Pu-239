/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */
// Ajax Chat language Object:
var ajaxChatLang = {
    login: '%s ჩატში შემოვიდა.',
    logout: '%s ჩატიდან გავიდა.',
    logoutTimeout: '%s ჩატი დატოვა (დრო ამოიწურა).',
    logoutIP: '%s ჩატი დატოვა (არასწორი IP მისამართი).',
    logoutKicked: '%s ჩატი დატოვა (ამოარტყეს).',
    channelEnter: '%s უერთდება არხს.',
    channelLeave: '%s ტოვებს არხს.',
    privmsg: '(ჩურჩულებს)',
    privmsgto: '(%s-ს უჩურჩულებს)',
    invite: '%s გეპაიჟებათ შეუერთდეთ %s-ს.',
    inviteto: '%s-თვის შექმნილი მოსაწვევი, რათა შეუერთდეს არხ %s-ს, გაგზავნილია.',
    uninvite: '%s თავის დაპატიჟებას არხ %s-თვის აუქმებს.',
    uninviteto: '%s-თვის დაწერილი, %s არხის  მოსაწვევის გაუქმება გაგხავნილია.',
    queryOpen: 'პირადი არხი %s-სთან გასხნილია.',
    queryClose: 'პირადი არხი %s-სთან დახურულია.',
    ignoreAdded: '%s იგნორირების სიას დაემატა.',
    ignoreRemoved: '%s იგნორირების სიიდან ამოღებულია.',
    ignoreList: 'იგნორირებულები:',
    ignoreListEmpty: 'იგნორირებული წევრები არაა.',
    who: 'ხაზზე არიან:',
    whoChannel: 'არხ %s-ში ხაზზე არიან:',
    whoEmpty: 'მოცემულ არხში ხაზზე არავინაა.',
    list: 'ხელმისაწვდომი არხები:',
    bans: 'დაბლოკილი წევრები:',
    bansEmpty: 'დაბლოკილი წევრები არაა.',
    unban: '%s წევრის ბლოკი მოხსნილია.',
    whois: '%s წევრის - IP მისამართი:',
    whereis: 'წევრი %s იმყოფება %s არხში.',
    roll: '%s აგდებს %s-ს და იღებს  %s-ს.',
    nick: '%s ახლა ცნობილია როგორც %s.',
    toggleUserMenu: 'წევრის მენიუს %s-თვის ჩართვა/გათიშვა',
    userMenuLogout: 'გასვლა',
    userMenuWho: 'ჩამოწერე ხაზზე ვინაა',
    userMenuList: 'ჩამოწერე ხელმისაწვდომი არხები',
    userMenuAction: 'აღწერე ქმედება',
    userMenuRoll: 'კამათლების გაგორება',
    userMenuNick: 'მეტსახელის შეცვლა',
    userMenuEnterPrivateRoom: 'პირად ოთახში შესვლა',
    userMenuSendPrivateMessage: 'პირადი მიმოწერა',
    userMenuDescribe: 'პირადი ქმედების გაგზავნა',
    userMenuOpenPrivateChannel: 'გახსენი პირადი არხი',
    userMenuClosePrivateChannel: 'დახურე პირადი არხი',
    userMenuInvite: 'დაპატიჟება',
    userMenuUninvite: 'დაპატიჟების გაუქმება',
    userMenuIgnore: 'იგნორირება/მიღება',
    userMenuIgnoreList: 'ჩამოწერე იგნორირებულები',
    userMenuWhereis: 'მაჩვენე არხი',
    userMenuKick: 'ამორტყმა/დაბლოკვა',
    userMenuBans: 'დაბლოკილების ჩამოწერა',
    userMenuWhois: 'IP-ს ჩვენება',
    unbanUser: '%s წევრის ბლოკის მოხსნა',
    joinChannel: '%s არხში შესვლა',
    cite: '%s თქვა:',
    urlDialog: 'გთხოვთ შეიყვანოთ ვებ-გვერდის მისამართი (URL):',
    deleteMessage: 'გზავნილის წაშლა',
    deleteMessageConfirm: 'მართლა წავშალოთ ეს გზავნილი?',
    errorCookiesRequired: 'ჩატისთვის cookies არიან საჭირო.',
    errorUserNameNotFound: 'შეცდომა: წევრი %s არ მოიძებნა.',
    errorMissingText: 'შეცდომა: გზავნილის ტექსტი აკლია.',
    errorMissingUserName: 'შეცდომა: აკლია მეტსახელი.',
    errorInvalidUserName: 'შეცდომა: არასწორი მეტსახელი.',
    errorUserNameInUse: 'შეცდომა: მეტსახელი დაკავებულია.',
    errorMissingChannelName: 'შეცდომა: აკლია არხის სახელი.',
    errorInvalidChannelName: 'შეცდომა: არხის სახელი - %s - არასწორია',
    errorPrivateMessageNotAllowed: 'შეცდომა: პირადი მიმოწერა აკრზალულია.',
    errorInviteNotAllowed: 'შეცდომა: უფლება არ გაქვთ მიმდინარე არხში ვინმე მოიწვიოთ.',
    errorUninviteNotAllowed: 'შეცდომა: უფლება არ გაქავთ მინდინარე არხიდან ვინმესი გაგდება.',
    errorNoOpenQuery: 'შეცდომა: პირადი არხები გახსნილი არაა.',
    errorKickNotAllowed: 'შეცდომა: უფლება არ გაგჩნიათ %s-ს ამოარტყათ.',
    errorCommandNotAllowed: 'შეცდომა: ბრზანება - %s - აკრძალულია',
    errorUnknownCommand: 'შეცდომა: ბრძანება - %s - უცნობია',
    errorMaxMessageRate: 'შეცდომა: თქვენ მიაღწიეთ წუთში მაქსიმალურ შესაძლებელ გზავნილების რიცხვს.',
    errorConnectionTimeout: 'შეცდომა: კავშირს ვადა გაუვიდა. გთხოვთ, კიდევ სცადეთ.',
    errorConnectionStatus: 'შეცდომა: კავშირის სტატუსი: %s',
    errorSoundIO: 'შეცდომა: ხმის ფაილი ვერ ჩაიტვირთა (Flash IO Error).',
    errorSocketIO: 'შეცდომა: სერვერის სოკეტთან დაკავშირება ჩაიშალა (Flash IO Error).',
    errorSocketSecurity: 'შეცდომა: სერვერის სოკეტთან დაკავშირება ჩაიშალა (Flash Security Error).',
    errorDOMSyntax: 'შეცდომა: არასწორი DOM სინტაქსი (DOM ID: %s).',
    errorNotEnoughRep: 'Error: Not enough reputaion points.',
    errorNotEnoughKarma: 'Error: Not enough karma(seedbonus) points.'
};
