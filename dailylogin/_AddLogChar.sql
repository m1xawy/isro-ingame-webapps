-- Rigid Daily Login
IF(@EventID = 6 OR @EventID = 4)
BEGIN
    DECLARE @JID int = (SELECT UserJID FROM [SILKROAD_R_SHARD].[dbo].[_User] WHERE CharID = @CharID)
    EXEC [SILKROAD_R_ACCOUNT].[dbo].[_Rigid_Login_Event_Playtime_Check] @JID, @EventID
END