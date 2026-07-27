package ng.com.codizium.fintrack.ui.screens.expense

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.items
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Add
import androidx.compose.material.icons.filled.KeyboardArrowDown
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.clip
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.navigation.NavController
import ng.com.codizium.fintrack.data.model.*
import ng.com.codizium.fintrack.navigation.Routes
import ng.com.codizium.fintrack.ui.screens.dashboard.MainTopBar
import ng.com.codizium.fintrack.ui.theme.*

@Composable
fun ExpenseScreen(navController: NavController) {
    val expenseList = MockData.expenseTransactions

    LazyColumn(
        modifier = Modifier
            .fillMaxSize()
            .background(BackgroundGray),
        contentPadding = PaddingValues(bottom = 16.dp)
    ) {
        item { MainTopBar() }

        item {
            Row(
                modifier = Modifier
                    .fillMaxWidth()
                    .padding(horizontal = 16.dp, vertical = 16.dp),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                Text("Expenses", fontSize = 20.sp, fontWeight = FontWeight.Bold, color = TextPrimary)
                Button(
                    onClick = { navController.navigate(Routes.ADD_EXPENSE) },
                    shape = RoundedCornerShape(8.dp),
                    colors = ButtonDefaults.buttonColors(containerColor = ExpenseRed),
                    contentPadding = PaddingValues(horizontal = 12.dp, vertical = 8.dp)
                ) {
                    Icon(Icons.Filled.Add, null, modifier = Modifier.size(16.dp))
                    Spacer(Modifier.width(4.dp))
                    Text("Add Expense", fontSize = 13.sp)
                }
            }
        }

        item {
            Row(
                modifier = Modifier.padding(horizontal = 16.dp),
                verticalAlignment = Alignment.CenterVertically,
                horizontalArrangement = Arrangement.spacedBy(8.dp)
            ) {
                Row(
                    modifier = Modifier
                        .clip(RoundedCornerShape(8.dp))
                        .background(CardWhite)
                        .padding(horizontal = 10.dp, vertical = 6.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    Text("This Month", fontSize = 12.sp, color = TextSecondary)
                    Icon(Icons.Filled.KeyboardArrowDown, null, modifier = Modifier.size(14.dp), tint = TextSecondary)
                }
                OutlinedButton(
                    onClick = {},
                    shape = RoundedCornerShape(8.dp),
                    contentPadding = PaddingValues(horizontal = 10.dp, vertical = 6.dp),
                    border = ButtonDefaults.outlinedButtonBorder(enabled = true)
                ) {
                    Text("Filter", fontSize = 12.sp, color = TextSecondary)
                }
            }
            Spacer(Modifier.height(12.dp))
        }

        item {
            Row(
                modifier = Modifier
                    .fillMaxWidth()
                    .padding(horizontal = 16.dp)
                    .clip(RoundedCornerShape(topStart = 10.dp, topEnd = 10.dp))
                    .background(SurfaceGray)
                    .padding(horizontal = 12.dp, vertical = 10.dp)
            ) {
                Text("Description", fontSize = 11.sp, fontWeight = FontWeight.SemiBold, color = TextSecondary, modifier = Modifier.weight(1.8f))
                Text("Amount", fontSize = 11.sp, fontWeight = FontWeight.SemiBold, color = TextSecondary, modifier = Modifier.weight(1f))
                Text("Date", fontSize = 11.sp, fontWeight = FontWeight.SemiBold, color = TextSecondary, modifier = Modifier.weight(1f))
                Text("Created By", fontSize = 11.sp, fontWeight = FontWeight.SemiBold, color = TextSecondary, modifier = Modifier.weight(1.2f))
            }
        }

        items(expenseList) { tx ->
            Surface(
                modifier = Modifier
                    .fillMaxWidth()
                    .padding(horizontal = 16.dp),
                color = CardWhite,
                onClick = { navController.navigate(Routes.transactionDetail(tx.id)) }
            ) {
                Column {
                    Row(
                        modifier = Modifier.padding(horizontal = 12.dp, vertical = 12.dp),
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Text(tx.description, fontSize = 12.sp, color = TextPrimary, modifier = Modifier.weight(1.8f))
                        Text(formatAmount(tx.amount), fontSize = 12.sp, color = ExpenseRed, fontWeight = FontWeight.Medium, modifier = Modifier.weight(1f))
                        Text(tx.date, fontSize = 11.sp, color = TextSecondary, modifier = Modifier.weight(1f))
                        Text(tx.createdBy, fontSize = 11.sp, color = TextSecondary, modifier = Modifier.weight(1.2f))
                    }
                    HorizontalDivider(color = BorderGray, thickness = 0.5.dp)
                }
            }
        }

        item {
            Row(
                modifier = Modifier
                    .fillMaxWidth()
                    .padding(horizontal = 16.dp)
                    .clip(RoundedCornerShape(bottomStart = 10.dp, bottomEnd = 10.dp))
                    .background(ExpenseRedSurface)
                    .padding(horizontal = 12.dp, vertical = 10.dp)
            ) {
                Text("Total", fontSize = 13.sp, fontWeight = FontWeight.Bold, color = TextPrimary, modifier = Modifier.weight(1.8f))
                Text(formatAmount(MockData.totalExpenses), fontSize = 13.sp, fontWeight = FontWeight.Bold, color = ExpenseRed, modifier = Modifier.weight(1f))
                Spacer(Modifier.weight(2.2f))
            }
        }
    }
}
