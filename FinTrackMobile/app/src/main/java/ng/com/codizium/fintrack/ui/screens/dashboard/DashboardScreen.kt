package ng.com.codizium.fintrack.ui.screens.dashboard

import androidx.compose.foundation.Canvas
import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Add
import androidx.compose.material.icons.filled.ArrowDownward
import androidx.compose.material.icons.filled.ArrowUpward
import androidx.compose.material.icons.filled.KeyboardArrowDown
import androidx.compose.material.icons.filled.Menu
import androidx.compose.material.icons.filled.Notifications
import androidx.compose.material.icons.filled.Star
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.geometry.Offset
import androidx.compose.ui.geometry.Size
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.StrokeCap
import androidx.compose.ui.graphics.drawscope.Stroke
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.navigation.NavController
import ng.com.codizium.fintrack.data.model.*
import ng.com.codizium.fintrack.navigation.Routes
import ng.com.codizium.fintrack.ui.theme.*

@Composable
fun DashboardScreen(navController: NavController) {
    val recentTransactions = MockData.transactions.take(5)

    LazyColumn(
        modifier = Modifier
            .fillMaxSize()
            .background(BackgroundGray),
        contentPadding = PaddingValues(bottom = 16.dp)
    ) {
        item { MainTopBar() }

        item {
            Column(modifier = Modifier.padding(horizontal = 16.dp)) {
                Spacer(Modifier.height(16.dp))
                Row(
                    modifier = Modifier.fillMaxWidth(),
                    horizontalArrangement = Arrangement.SpaceBetween,
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Text("Dashboard", fontSize = 20.sp, fontWeight = FontWeight.Bold, color = TextPrimary)
                    Row(
                        modifier = Modifier
                            .clip(RoundedCornerShape(8.dp))
                            .background(CardWhite)
                            .padding(horizontal = 10.dp, vertical = 6.dp),
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Text("This Month", fontSize = 12.sp, color = TextSecondary)
                        Spacer(Modifier.width(4.dp))
                        Icon(Icons.Filled.Menu, null, modifier = Modifier.size(14.dp), tint = TextSecondary)
                    }
                }
                Spacer(Modifier.height(4.dp))
                Text("Welcome back, John 👋", fontSize = 14.sp, color = TextSecondary)
                Spacer(Modifier.height(16.dp))
            }
        }

        // Stat cards 2x2 grid
        item {
            Column(modifier = Modifier.padding(horizontal = 16.dp)) {
                Row(modifier = Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.spacedBy(12.dp)) {
                    StatCard(
                        modifier = Modifier.weight(1f),
                        label = "Total Income",
                        value = formatAmount(MockData.totalIncome),
                        change = "↑ 20% from last month",
                        changePositive = true,
                        bgColor = IncomeGreenSurface,
                        iconBg = IncomeGreen,
                        icon = Icons.Filled.ArrowUpward
                    )
                    StatCard(
                        modifier = Modifier.weight(1f),
                        label = "Total Expenses",
                        value = formatAmount(MockData.totalExpenses),
                        change = "↑ 8% from last month",
                        changePositive = false,
                        bgColor = ExpenseRedSurface,
                        iconBg = ExpenseRed,
                        icon = Icons.Filled.ArrowDownward
                    )
                }
                Spacer(Modifier.height(12.dp))
                Row(modifier = Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.spacedBy(12.dp)) {
                    StatCard(
                        modifier = Modifier.weight(1f),
                        label = "Balance",
                        value = formatAmount(MockData.balance),
                        change = "↑ 12% from last month",
                        changePositive = true,
                        bgColor = BalanceBlueSurface,
                        iconBg = FinTrackBlue,
                        icon = Icons.Filled.Star
                    )
                    StatCard(
                        modifier = Modifier.weight(1f),
                        label = "Transactions",
                        value = "25",
                        change = "↑ 5 from last month",
                        changePositive = true,
                        bgColor = TransactionPurpleSurface,
                        iconBg = TransactionPurple,
                        icon = Icons.Filled.Menu
                    )
                }
                Spacer(Modifier.height(20.dp))
            }
        }

        // Recent Transactions
        item {
            Row(
                modifier = Modifier
                    .fillMaxWidth()
                    .padding(horizontal = 16.dp),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                Text("Recent Transactions", fontSize = 15.sp, fontWeight = FontWeight.SemiBold, color = TextPrimary)
                TextButton(onClick = {}) {
                    Text("View all", fontSize = 13.sp, color = FinTrackBlue)
                }
            }
        }

        items(recentTransactions) { tx ->
            TransactionRow(tx) { navController.navigate(Routes.transactionDetail(tx.id)) }
        }

        // Quick Actions
        item {
            Column(modifier = Modifier.padding(horizontal = 16.dp)) {
                Spacer(Modifier.height(20.dp))
                Text("Quick Actions", fontSize = 15.sp, fontWeight = FontWeight.SemiBold, color = TextPrimary)
                Spacer(Modifier.height(12.dp))
                Row(modifier = Modifier.fillMaxWidth(), horizontalArrangement = Arrangement.spacedBy(12.dp)) {
                    Button(
                        onClick = { navController.navigate(Routes.ADD_INCOME) },
                        modifier = Modifier.weight(1f).height(44.dp),
                        shape = RoundedCornerShape(10.dp),
                        colors = ButtonDefaults.buttonColors(containerColor = IncomeGreen)
                    ) {
                        Icon(Icons.Filled.Add, null, modifier = Modifier.size(16.dp))
                        Spacer(Modifier.width(4.dp))
                        Text("Add Income", fontSize = 13.sp, fontWeight = FontWeight.SemiBold)
                    }
                    Button(
                        onClick = { navController.navigate(Routes.ADD_EXPENSE) },
                        modifier = Modifier.weight(1f).height(44.dp),
                        shape = RoundedCornerShape(10.dp),
                        colors = ButtonDefaults.buttonColors(containerColor = ExpenseRed)
                    ) {
                        Icon(Icons.Filled.Add, null, modifier = Modifier.size(16.dp))
                        Spacer(Modifier.width(4.dp))
                        Text("Add Expense", fontSize = 13.sp, fontWeight = FontWeight.SemiBold)
                    }
                }
                Spacer(Modifier.height(20.dp))
            }
        }

        // Spending Overview
        item {
            Card(
                modifier = Modifier
                    .fillMaxWidth()
                    .padding(horizontal = 16.dp),
                shape = RoundedCornerShape(12.dp),
                colors = CardDefaults.cardColors(containerColor = CardWhite),
                elevation = CardDefaults.cardElevation(1.dp)
            ) {
                Column(modifier = Modifier.padding(16.dp)) {
                    Text("Spending Overview", fontSize = 15.sp, fontWeight = FontWeight.SemiBold, color = TextPrimary)
                    Spacer(Modifier.height(16.dp))
                    Row(
                        modifier = Modifier.fillMaxWidth(),
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Box(modifier = Modifier.size(140.dp), contentAlignment = Alignment.Center) {
                            DonutChart()
                            Column(horizontalAlignment = Alignment.CenterHorizontally) {
                                Text("₦200,000", fontSize = 11.sp, fontWeight = FontWeight.Bold, color = TextPrimary)
                                Text("Total Expenses", fontSize = 9.sp, color = TextSecondary)
                            }
                        }
                        Spacer(Modifier.width(20.dp))
                        Column(verticalArrangement = Arrangement.spacedBy(10.dp)) {
                            ChartLegendItem(ChartTransport, "Transport", "40%")
                            ChartLegendItem(ChartOffice, "Office", "25%")
                            ChartLegendItem(ChartUtilities, "Utilities", "20%")
                            ChartLegendItem(ChartOthers, "Others", "15%")
                        }
                    }
                }
            }
            Spacer(Modifier.height(16.dp))
        }
    }
}

@Composable
private fun StatCard(
    modifier: Modifier = Modifier,
    label: String,
    value: String,
    change: String,
    changePositive: Boolean,
    bgColor: Color,
    iconBg: Color,
    icon: androidx.compose.ui.graphics.vector.ImageVector
) {
    Card(
        modifier = modifier,
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(containerColor = bgColor),
        elevation = CardDefaults.cardElevation(0.dp)
    ) {
        Column(modifier = Modifier.padding(12.dp)) {
            Box(
                modifier = Modifier
                    .size(32.dp)
                    .clip(RoundedCornerShape(8.dp))
                    .background(iconBg),
                contentAlignment = Alignment.Center
            ) {
                Icon(icon, null, tint = Color.White, modifier = Modifier.size(16.dp))
            }
            Spacer(Modifier.height(8.dp))
            Text(label, fontSize = 11.sp, color = TextSecondary)
            Text(value, fontSize = 15.sp, fontWeight = FontWeight.Bold, color = TextPrimary)
            Spacer(Modifier.height(4.dp))
            Text(
                change,
                fontSize = 10.sp,
                color = if (changePositive) IncomeGreen else ExpenseRed
            )
        }
    }
}

@Composable
fun TransactionRow(tx: Transaction, onClick: () -> Unit) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(horizontal = 16.dp, vertical = 4.dp)
            .clickable(onClick = onClick),
        shape = RoundedCornerShape(10.dp),
        colors = CardDefaults.cardColors(containerColor = CardWhite),
        elevation = CardDefaults.cardElevation(1.dp)
    ) {
        Row(
            modifier = Modifier.padding(12.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            Box(
                modifier = Modifier
                    .size(36.dp)
                    .clip(CircleShape)
                    .background(if (tx.type == TransactionType.INCOME) IncomeGreenSurface else ExpenseRedSurface),
                contentAlignment = Alignment.Center
            ) {
                Icon(
                    if (tx.type == TransactionType.INCOME) Icons.Filled.ArrowUpward else Icons.Filled.ArrowDownward,
                    null,
                    tint = if (tx.type == TransactionType.INCOME) IncomeGreen else ExpenseRed,
                    modifier = Modifier.size(18.dp)
                )
            }
            Spacer(Modifier.width(10.dp))
            Column(modifier = Modifier.weight(1f)) {
                Text(tx.description, fontSize = 13.sp, fontWeight = FontWeight.Medium, color = TextPrimary)
                Text(tx.createdBy, fontSize = 11.sp, color = TextSecondary)
            }
            Column(horizontalAlignment = Alignment.End) {
                Text(
                    formatAmount(tx.amount),
                    fontSize = 13.sp,
                    fontWeight = FontWeight.SemiBold,
                    color = if (tx.type == TransactionType.INCOME) IncomeGreen else ExpenseRed
                )
                Text(tx.date, fontSize = 10.sp, color = TextTertiary)
            }
            Spacer(Modifier.width(8.dp))
            Box(
                modifier = Modifier
                    .clip(RoundedCornerShape(4.dp))
                    .background(if (tx.type == TransactionType.INCOME) IncomeGreenSurface else ExpenseRedSurface)
                    .padding(horizontal = 6.dp, vertical = 2.dp)
            ) {
                Text(
                    if (tx.type == TransactionType.INCOME) "Income" else "Expense",
                    fontSize = 10.sp,
                    color = if (tx.type == TransactionType.INCOME) IncomeGreen else ExpenseRed,
                    fontWeight = FontWeight.Medium
                )
            }
        }
    }
}

@Composable
private fun DonutChart() {
    val segments = listOf(
        0.40f to ChartTransport,
        0.25f to ChartOffice,
        0.20f to ChartUtilities,
        0.15f to ChartOthers,
    )
    Canvas(modifier = Modifier.size(140.dp)) {
        val strokeWidth = size.minDimension * 0.16f
        val radius = (size.minDimension - strokeWidth) / 2f
        val topLeft = Offset((size.width - radius * 2) / 2f, (size.height - radius * 2) / 2f)
        val arcSize = Size(radius * 2, radius * 2)
        var startAngle = -90f
        segments.forEach { (fraction, color) ->
            val sweepAngle = fraction * 360f
            drawArc(
                color = color,
                startAngle = startAngle,
                sweepAngle = sweepAngle - 3f,
                useCenter = false,
                style = Stroke(width = strokeWidth, cap = StrokeCap.Butt),
                topLeft = topLeft,
                size = arcSize
            )
            startAngle += sweepAngle
        }
    }
}

@Composable
private fun ChartLegendItem(color: Color, label: String, percent: String) {
    Row(verticalAlignment = Alignment.CenterVertically) {
        Box(modifier = Modifier.size(10.dp).clip(CircleShape).background(color))
        Spacer(Modifier.width(6.dp))
        Text(label, fontSize = 12.sp, color = TextSecondary, modifier = Modifier.width(60.dp))
        Text(percent, fontSize = 12.sp, fontWeight = FontWeight.SemiBold, color = TextPrimary)
    }
}

@Composable
fun MainTopBar(orgName: String = "John Finance Hub") {
    Row(
        modifier = Modifier
            .fillMaxWidth()
            .background(CardWhite)
            .padding(horizontal = 16.dp, vertical = 12.dp),
        verticalAlignment = Alignment.CenterVertically
    ) {
        Row(verticalAlignment = Alignment.CenterVertically) {
            Box(
                modifier = Modifier
                    .size(28.dp)
                    .clip(RoundedCornerShape(6.dp))
                    .background(FinTrackBlue),
                contentAlignment = Alignment.Center
            ) {
                Text("FT", color = Color.White, fontSize = 10.sp, fontWeight = FontWeight.Bold)
            }
            Spacer(Modifier.width(6.dp))
            Text("FinTrack", fontSize = 15.sp, fontWeight = FontWeight.Bold, color = TextPrimary)
        }
        Spacer(Modifier.weight(1f))
        Row(
            modifier = Modifier
                .clip(RoundedCornerShape(8.dp))
                .background(SurfaceGray)
                .padding(horizontal = 8.dp, vertical = 4.dp),
            verticalAlignment = Alignment.CenterVertically
        ) {
            Text(orgName, fontSize = 12.sp, fontWeight = FontWeight.Medium, color = TextPrimary)
            Icon(Icons.Filled.KeyboardArrowDown, null, modifier = Modifier.size(14.dp), tint = TextSecondary)
        }
        Spacer(Modifier.width(8.dp))
        BadgedBox(badge = { Badge { Text("2") } }) {
            Icon(Icons.Filled.Notifications, null, tint = TextSecondary, modifier = Modifier.size(22.dp))
        }
        Spacer(Modifier.width(10.dp))
        Box(
            modifier = Modifier
                .size(32.dp)
                .clip(CircleShape)
                .background(FinTrackBlue),
            contentAlignment = Alignment.Center
        ) {
            Text("JD", color = Color.White, fontSize = 11.sp, fontWeight = FontWeight.Bold)
        }
    }
}
