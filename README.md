## 会议室预订小程序后端

### 注意事项

为了防止用户信息被遍历，/api/users/{id} 接口，需要传入 user_id 加密过后的字符串

某些接口（比如 /api/bookings POST）的 user_id 输出为加密后的字符串，更新数据的时候注意这个情况

user 和 booking 用到用户 id 时隐藏掉真实 user_id 只展示 加密后的 user_id

加密 user_id 使用的算法导致加密出来的 user_id 每次都不一样 没办法做字符串比较，这个算法可以随便修改，只要可逆就行
