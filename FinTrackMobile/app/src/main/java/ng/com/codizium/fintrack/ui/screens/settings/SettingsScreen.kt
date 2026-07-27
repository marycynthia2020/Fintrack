package ng.com.codizium.fintrack.ui.screens.settings

import androidx.compose.foundation.BorderStroke
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.*
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.KeyboardArrowDown
import androidx.compose.material3.*
import androidx.compose.runtime.*
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.SolidColor
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.navigation.NavController
import ng.com.codizium.fintrack.navigation.Routes
import ng.com.codizium.fintrack.ui.screens.dashboard.MainTopBar
import ng.com.codizium.fintrack.ui.theme.*

@Composable
fun SettingsScreen(navController: NavController) {
    var orgName by remember { mutableStateOf("John Finance Hub") }
    var description by remember { mutableStateOf("We manage income and expenses effectively.") }
    var selectedTimezone by remember { mutableStateOf("(GMT+01:00) West Africa Time") }
    var timezoneExpanded by remember { mutableStateOf(false) }

    val timezones = listOf(
        "(GMT+01:00) West Africa Time",
        "(GMT+00:00) UTC",
        "(GMT-05:00) Eastern Standard Time",
        "(GMT-06:00) Central Standard Time",
        "(GMT+03:00) East Africa Time",
    )

    LazyColumnSettingsContent(
        orgName = orgName,
        onOrgNameChange = { orgName = it },
        description = description,
        onDescriptionChange = { description = it },
        selectedTimezone = selectedTimezone,
        timezoneExpanded = timezoneExpanded,
        timezones = timezones,
        onTimezoneExpand = { timezoneExpanded = it },
        onTimezoneSelect = { selectedTimezone = it; timezoneExpanded = false },
        onSave = { navController.popBackStack() },
        onLogout = {
            navController.navigate(Routes.LOGIN) {
                popUpTo(0) { inclusive = true }
            }
        }
    )
}

@Composable
private fun LazyColumnSettingsContent(
    orgName: String,
    onOrgNameChange: (String) -> Unit,
    description: String,
    onDescriptionChange: (String) -> Unit,
    selectedTimezone: String,
    timezoneExpanded: Boolean,
    timezones: List<String>,
    onTimezoneExpand: (Boolean) -> Unit,
    onTimezoneSelect: (String) -> Unit,
    onSave: () -> Unit,
    onLogout: () -> Unit,
) {
    Column(
        modifier = Modifier
            .fillMaxSize()
            .background(BackgroundGray)
    ) {
        MainTopBar()

        Column(
            modifier = Modifier
                .fillMaxSize()
                .verticalScroll(rememberScrollState())
                .padding(16.dp)
        ) {
            Row(
                modifier = Modifier.fillMaxWidth(),
                horizontalArrangement = Arrangement.SpaceBetween,
                verticalAlignment = Alignment.CenterVertically
            ) {
                Text("Organization Settings", fontSize = 18.sp, fontWeight = FontWeight.Bold, color = TextPrimary)
            }

            Spacer(Modifier.height(16.dp))

            Card(
                modifier = Modifier.fillMaxWidth(),
                shape = RoundedCornerShape(12.dp),
                colors = CardDefaults.cardColors(containerColor = CardWhite),
                elevation = CardDefaults.cardElevation(2.dp)
            ) {
                Column(modifier = Modifier.padding(20.dp)) {

                    Text("Organization Name", fontSize = 13.sp, fontWeight = FontWeight.Medium, color = TextPrimary)
                    Spacer(Modifier.height(6.dp))
                    OutlinedTextField(
                        value = orgName,
                        onValueChange = onOrgNameChange,
                        modifier = Modifier.fillMaxWidth(),
                        shape = RoundedCornerShape(10.dp),
                        singleLine = true,
                        colors = OutlinedTextFieldDefaults.colors(
                            focusedBorderColor = FinTrackBlue,
                            unfocusedBorderColor = BorderGray,
                        )
                    )

                    Spacer(Modifier.height(16.dp))

                    Text("Description", fontSize = 13.sp, fontWeight = FontWeight.Medium, color = TextPrimary)
                    Spacer(Modifier.height(6.dp))
                    OutlinedTextField(
                        value = description,
                        onValueChange = onDescriptionChange,
                        modifier = Modifier.fillMaxWidth().height(100.dp),
                        shape = RoundedCornerShape(10.dp),
                        maxLines = 4,
                        colors = OutlinedTextFieldDefaults.colors(
                            focusedBorderColor = FinTrackBlue,
                            unfocusedBorderColor = BorderGray,
                        )
                    )

                    Spacer(Modifier.height(16.dp))

                    Text("Timezone", fontSize = 13.sp, fontWeight = FontWeight.Medium, color = TextPrimary)
                    Spacer(Modifier.height(6.dp))

                    ExposedDropdownMenuBox(
                        expanded = timezoneExpanded,
                        onExpandedChange = onTimezoneExpand
                    ) {
                        OutlinedTextField(
                            value = selectedTimezone,
                            onValueChange = {},
                            readOnly = true,
                            trailingIcon = {
                                Icon(
                                    Icons.Filled.KeyboardArrowDown,
                                    null,
                                    tint = TextTertiary,
                                    modifier = Modifier.size(18.dp)
                                )
                            },
                            modifier = Modifier.fillMaxWidth().menuAnchor(MenuAnchorType.PrimaryNotEditable),
                            shape = RoundedCornerShape(10.dp),
                            colors = OutlinedTextFieldDefaults.colors(
                                focusedBorderColor = FinTrackBlue,
                                unfocusedBorderColor = BorderGray,
                            )
                        )
                        ExposedDropdownMenu(
                            expanded = timezoneExpanded,
                            onDismissRequest = { onTimezoneExpand(false) }
                        ) {
                            timezones.forEach { tz ->
                                DropdownMenuItem(
                                    text = { Text(tz, fontSize = 13.sp) },
                                    onClick = { onTimezoneSelect(tz) }
                                )
                            }
                        }
                    }

                    Spacer(Modifier.height(28.dp))

                    Button(
                        onClick = onSave,
                        modifier = Modifier.fillMaxWidth().height(48.dp),
                        shape = RoundedCornerShape(10.dp),
                        colors = ButtonDefaults.buttonColors(containerColor = FinTrackBlue)
                    ) {
                        Text("Save Changes", fontSize = 15.sp, fontWeight = FontWeight.SemiBold)
                    }
                }
            }

            Spacer(Modifier.height(20.dp))

            // Logout section
            Card(
                modifier = Modifier.fillMaxWidth(),
                shape = RoundedCornerShape(12.dp),
                colors = CardDefaults.cardColors(containerColor = CardWhite),
                elevation = CardDefaults.cardElevation(1.dp)
            ) {
                Column(modifier = Modifier.padding(20.dp)) {
                    Text("Account", fontSize = 14.sp, fontWeight = FontWeight.SemiBold, color = TextPrimary)
                    Spacer(Modifier.height(12.dp))
                    OutlinedButton(
                        onClick = onLogout,
                        modifier = Modifier.fillMaxWidth().height(48.dp),
                        shape = RoundedCornerShape(10.dp),
                        colors = ButtonDefaults.outlinedButtonColors(contentColor = ExpenseRed),
                        border = BorderStroke(1.dp, SolidColor(ExpenseRed))
                    ) {
                        Text("Sign Out", fontSize = 14.sp, color = ExpenseRed, fontWeight = FontWeight.SemiBold)
                    }
                }
            }
        }
    }
}
