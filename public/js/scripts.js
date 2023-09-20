import { NotificationsService } from "./services/notifications.js";
const notificationsService = new NotificationsService();
$(document).ready(function () {
    $("#btn_unread_notifications").on("click", async function(e) {
        let response = await notificationsService.markAsRead();
        $("#bgnumnotifications").remove();
    });
});
