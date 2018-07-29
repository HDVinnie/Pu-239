/*
 * @package AJAX_Chat
 * @author Sebastian Tschan
 * @copyright (c) Sebastian Tschan
 * @license Modified MIT License
 * @link https://blueimp.net/ajax/
 */
// Ajax Chat language Object:
var ajaxChatLang = {
    login: '%s さんがログインしました',
    logout: '%s さんがログアウトしました',
    logoutTimeout: '%s さんが強制的にログアウトされました (タイムアウト)',
    logoutIP: '%s さんが強制的にログアウトされました (不正な IPアドレス)',
    logoutKicked: '%s さんが強制的にログアウトされました (キック).',
    channelEnter: '%s さんが入室しました',
    channelLeave: '%s さんが退室しました',
    privmsg: '(プライベートメッセージ)',
    privmsgto: '(%s さんへプライベートメッセージ)',
    invite: '%s さんから チャンネル %s への招待 が届いています',
    inviteto: '%s さんへ チャンネル %s への招待 を送りました',
    uninvite: '%s さんから チャンネル %s への招待 を取り消されました',
    uninviteto: '%s さんの チャンネル %s への招待 を取り消しました',
    queryOpen: '二人きりモードを %s さんと開始しました',
    queryClose: '%s さんとの二人きりモードを終了しました',
    ignoreAdded: '%s さんを無視ユーザーリストに追加しました',
    ignoreRemoved: '%s さんを無理ユーザーリストから削除しました',
    ignoreList: '無視ユーザーリスト :',
    ignoreListEmpty: 'あなたはどのユーザーも無視していません',
    who: 'オンラインユーザー :',
    whoChannel: 'チャンネル %s に入室中のユーザー :',
    whoEmpty: 'そのチャンネルに入室中のユーザーは一人もいません',
    list: '入室可能なチャンネル :',
    bans: 'アクセス禁止ユーザーリスト :',
    bansEmpty: 'アクセス禁止されたユーザーは一人もいません',
    unban: 'ユーザー %s のアクセス禁止を取り消しました',
    whois: 'ユーザー %s の IPアドレス :',
    whereis: 'ユーザー　%s はチャンネル %s にいます',
    roll: '%s さんがサイコロを振りました。 %s - 結果 %s',
    nick: '%s さんのニックネームは以降 %s です',
    toggleUserMenu: '%s さんのユーザーメニューを表示する/表示しない',
    userMenuLogout: 'ログアウト',
    userMenuWho: 'オンラインユーザーリスト',
    userMenuList: '入室可能なチャンネルのリスト',
    userMenuAction: '感情を表現する',
    userMenuRoll: 'サイコロを振る',
    userMenuNick: 'ニックネーム',
    userMenuEnterPrivateRoom: 'プライベートルームへ移動する',
    userMenuSendPrivateMessage: 'プライベートメッセージを送る',
    userMenuDescribe: '感情を表現する',
    userMenuOpenPrivateChannel: '二人きりモードを開始する',
    userMenuClosePrivateChannel: '二人きりモードを終了する',
    userMenuInvite: '招待する',
    userMenuUninvite: '招待を取り消す',
    userMenuIgnore: '無視する/無視しない',
    userMenuIgnoreList: '無視ユーザーリスト',
    userMenuWhereis: 'ユーザーの居場所',
    userMenuKick: 'キック/アクセス禁止',
    userMenuBans: 'アクセス禁止ユーザーリスト',
    userMenuWhois: 'IPアドレス',
    unbanUser: 'ユーザー %s のアクセス禁止を取り消す',
    joinChannel: 'チャンネル %s へ移動する',
    cite: '%s さんが言いました :',
    urlDialog: 'サイトのアドレス (URL) を入力してください :',
    deleteMessage: 'このチャットメッセージを削除する',
    deleteMessageConfirm: 'チャットメッセージを本当に削除してもよろしいですか？',
    errorCookiesRequired: 'このチャットシステムを利用するには Cookie を有効にしておく必要があります',
    errorUserNameNotFound: 'エラー : ユーザー %s が見つかりませんでした',
    errorMissingText: 'エラー : メッセージが未入力です',
    errorMissingUserName: 'エラー : ユーザー名が未入力です',
    errorInvalidUserName: 'エラー : ユーザー名が正しくありません',
    errorUserNameInUse: 'エラー : そのユーザー名は既に使われています',
    errorMissingChannelName: 'エラー : チャンネル名が未入力です',
    errorInvalidChannelName: 'エラー : チャンネル名が正しくありません : %s',
    errorPrivateMessageNotAllowed: 'エラー : プライベートメッセージが許可されていません',
    errorInviteNotAllowed: 'エラー : あなたはこのチャンネルで誰かを招待することを許可されていません',
    errorUninviteNotAllowed: 'エラー : あなたはこのチャンネルで誰かの招待を取り消すことを許可されていません',
    errorNoOpenQuery: 'エラー : 二人きりモードが開始されていません',
    errorKickNotAllowed: 'エラー : あなたは %s さんをキックすることを許可されていません',
    errorCommandNotAllowed: 'エラー : コマンドが許可されていません : %s',
    errorUnknownCommand: 'エラー : コマンドが不正です : %s',
    errorMaxMessageRate: 'エラー : １分あたりに発言できる最大文字数を超えています',
    errorConnectionTimeout: 'エラー : 接続がタイムアウトしました。再度試してください。',
    errorConnectionStatus: 'エラー : 接続ステータス : %s',
    errorSoundIO: 'エラー : サウンドファイルの読み込みに失敗しました (Flash IO Error)',
    errorSocketIO: 'エラー : ソケットサーバへの接続に失敗しました (Flash IO Error)',
    errorSocketSecurity: 'エラー : ソケットサーバへの接続に失敗しました (Flash Security Error)',
    errorDOMSyntax: 'エラー : DOM の文法が不正です (DOM ID: %s)',
    errorNotEnoughRep: 'Error: Not enough reputaion points.',
    errorNotEnoughKarma: 'Error: Not enough karma(seedbonus) points.'
};
