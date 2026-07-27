package ng.com.codizium.fintrack.data.model

import java.text.NumberFormat
import java.util.Locale

data class Transaction(
    val id: String,
    val description: String,
    val amount: Long,
    val type: TransactionType,
    val date: String,
    val createdBy: String,
    val createdAt: String = "",
    val updatedBy: String = "",
    val updatedAt: String = "",
    val category: String = ""
)

enum class TransactionType { INCOME, EXPENSE }

data class Member(
    val id: String,
    val name: String,
    val email: String,
    val role: MemberRole,
    val joinedAt: String,
    val initials: String
)

enum class MemberRole { OWNER, ADMIN, MEMBER }

fun formatAmount(amount: Long): String {
    val nf = NumberFormat.getNumberInstance(Locale.US)
    return "₦${nf.format(amount)}"
}

object MockData {
    val transactions = listOf(
        Transaction("1", "Salary Payment", 150_000L, TransactionType.INCOME, "Jun 23, 2026", "John Doe",
            "Jun 23, 2026 10:30 AM", "John Doe", "Jun 23, 2026 10:30 AM", "Salary"),
        Transaction("2", "Freelance Work", 50_000L, TransactionType.INCOME, "Jun 21, 2026", "Mary Jane",
            "Jun 21, 2026 02:15 PM", "Mary Jane", "Jun 21, 2026 02:15 PM", "Freelance"),
        Transaction("3", "Transport", 10_000L, TransactionType.EXPENSE, "Jun 23, 2026", "John Doe",
            "Jun 23, 2026 09:00 AM", "Admin", "Jun 24, 2026 09:15 AM", "Transport"),
        Transaction("4", "Internet Subscription", 5_000L, TransactionType.EXPENSE, "Jun 22, 2026", "Mary Jane",
            "Jun 22, 2026 11:00 AM", "Mary Jane", "Jun 22, 2026 11:00 AM", "Utilities"),
        Transaction("5", "Office Supplies", 25_000L, TransactionType.EXPENSE, "Jun 20, 2026", "David Smith",
            "Jun 20, 2026 03:45 PM", "David Smith", "Jun 20, 2026 03:45 PM", "Office"),
        Transaction("6", "Investment Return", 100_000L, TransactionType.INCOME, "Jun 18, 2026", "John Doe",
            "Jun 18, 2026 01:00 PM", "John Doe", "Jun 18, 2026 01:00 PM", "Investment"),
        Transaction("7", "Gift Received", 20_000L, TransactionType.INCOME, "Jun 15, 2026", "David Smith",
            "Jun 15, 2026 04:00 PM", "David Smith", "Jun 15, 2026 04:00 PM", "Other"),
        Transaction("8", "Lunch", 8_000L, TransactionType.EXPENSE, "Jun 19, 2026", "Mary Jane",
            "Jun 19, 2026 01:30 PM", "Mary Jane", "Jun 19, 2026 01:30 PM", "Food"),
    )

    val members = listOf(
        Member("1", "John Doe", "john@example.com", MemberRole.OWNER, "Jun 10, 2026", "JD"),
        Member("2", "Mary Jane", "mary@example.com", MemberRole.ADMIN, "Jun 12, 2026", "MJ"),
        Member("3", "David Smith", "david@example.com", MemberRole.MEMBER, "Jun 15, 2026", "DS"),
    )

    val incomeTransactions get() = transactions.filter { it.type == TransactionType.INCOME }
    val expenseTransactions get() = transactions.filter { it.type == TransactionType.EXPENSE }
    val totalIncome get() = incomeTransactions.sumOf { it.amount }
    val totalExpenses get() = expenseTransactions.sumOf { it.amount }
    val balance get() = totalIncome - totalExpenses

    fun getById(id: String): Transaction? = transactions.find { it.id == id }
}
